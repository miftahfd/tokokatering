<?php

namespace App\Services;

use App\Helpers\Enums\InboxStatus;
use App\Helpers\Enums\InboxTechnicianStatus;
use App\Helpers\PredefinedCostHelper;
use App\Jobs\InboxJob;
use App\Models\Inbox;
use App\Models\InboxTechnician;
use App\Models\InboxTechnicianPredefinedLog;
use App\Models\User;
use Carbon\Carbon;

class InboxService {
    protected $inbox_technician_offer_minutes;

    public function __construct() {
        $this->inbox_technician_offer_minutes = env('INBOX_TECHNICIAN_OFFER_MINUTES') ? env('INBOX_TECHNICIAN_OFFER_MINUTES') : 1;
    }

    public function accept($maintenance_result, $ticket_maintenance) {
        $technician = auth()->user();
        $technician_id = $technician->id;

        $inbox = Inbox::where('id', $maintenance_result->last_inbox_id)->whereHas('maintenance_result')
        ->lockForUpdate()->first();

        if(!$inbox) return ['status' => false, 'message' => 'Data tidak ditemukan'];
        if($inbox->status != InboxStatus::LOOKING_TECHNICIAN) return ['status' => false, 'message' => 'Inbox tidak dapat di accept'];

        $inbox_technician = InboxTechnician::where('inbox_id', $inbox->id)
        ->where('technician_id', $technician_id)->lockForUpdate()->first();

        if(!$inbox_technician) return ['status' => false, 'message' => 'Data tidak ditemukan'];
        if($inbox_technician->status != InboxTechnicianStatus::OFFER) return ['status' => false, 'message' => 'Inbox sudah tidak ditawarkan kepada Anda'];
        $now = Carbon::now();
        $carbon_offer_end_time = Carbon::createFromTimestamp($inbox_technician->offer_end_time);
        if($now->greaterThan($carbon_offer_end_time)) return ['status' => false, 'message' => 'Waktu penawaran inbox sudah habis'];

        $inbox_technician->update(['status' => InboxTechnicianStatus::ACCEPT, 'accepted_at' => Carbon::now()->timestamp]);

        foreach($inbox->inbox_technicians as $value) {
            $value->technician->update(['is_have_inbox' => false]);
        }

        $mantools_backend_service = new MantoolsBackendService();
        $this->createOrder($maintenance_result, $inbox, $inbox_technician);
        $mantools_backend_service->assignTechnician($ticket_maintenance, $inbox, $inbox_technician);

        return ['status' => true, 'message' => 'Success', 'data' => $inbox_technician];
    }

    public function reject($data, $maintenance_result) {
        $technician = auth()->user();
        $technician_id = $technician->id;

        $inbox = Inbox::where('id', $maintenance_result->last_inbox_id)->whereHas('maintenance_result')
        ->lockForUpdate()->first();

        if(!$inbox) return ['status' => false, 'message' => 'Data tidak ditemukan'];
        if($inbox->status != InboxStatus::LOOKING_TECHNICIAN) return ['status' => false, 'message' => 'Inbox tidak dapat di reject'];

        $inbox_technician = InboxTechnician::where('inbox_id', $inbox->id)
        ->where('technician_id', $technician_id)->lockForUpdate()->first();

        if(!$inbox_technician) return ['status' => false, 'message' => 'Data tidak ditemukan'];
        if($inbox_technician->status != InboxTechnicianStatus::OFFER) return ['status' => false, 'message' => 'Inbox sudah tidak ditawarkan kepada Anda'];
        $now = Carbon::now();
        $carbon_offer_end_time = Carbon::createFromTimestamp($inbox_technician->offer_end_time);
        if($now->greaterThan($carbon_offer_end_time)) return ['status' => false, 'message' => 'Waktu penawaran inbox sudah habis'];

        $inbox_technician->update(['status' => InboxTechnicianStatus::REJECT, 'note' => $data->reason, 'rejected_at' => Carbon::now()->timestamp]);
        $technician->update(['is_have_inbox' => false]);

        $this->setNextOffer($maintenance_result, $inbox, $inbox_technician);

        return ['status' => true, 'message' => 'Success', 'data' => $inbox_technician];
    }

    public function expired($inbox_id, $technician_id) {
        $inbox = Inbox::where('id', $inbox_id)->whereHas('maintenance_result')->first();
        
        if(!$inbox) return false;

        $inbox_technician = InboxTechnician::where('inbox_id', $inbox_id)
        ->where('technician_id', $technician_id)->lockForUpdate()->first();

        if(!$inbox_technician) return false;
        if($inbox_technician->status != InboxTechnicianStatus::OFFER) return false;

        $inbox_technician->update(['status' => InboxTechnicianStatus::EXPIRED, 'expired_at' => Carbon::now()->timestamp]);
        User::find($technician_id)->update(['is_have_inbox' => 0]);

        $this->setNextOffer($inbox->maintenance_result, $inbox, $inbox_technician);

        return true;
    }

    private function createOrder($maintenance_result, $inbox, $inbox_technician) {
        $maintenance_result_service = new MaintenanceResultService();

        $inbox->update(['status' => InboxStatus::ASSIGN_TECHNICIAN]);

        $data = new \stdClass();
        $data->note = 'Accept Inbox';
        $maintenance_result_service->stepLog($data, $maintenance_result, true);

        $maintenance_result->update(['technician_id' => $inbox_technician->technician_id]);

        return true;
    }

    private function setNextOffer($maintenance_result, $inbox, $inbox_technician)  {
        $predefined = $inbox->predefined;
        $next_offer_sequence = (int)$inbox_technician->offer_sequence + 1;
        $next_offer = $this->getInboxTechnicianByOfferSequence($inbox->id, $next_offer_sequence);

        if($next_offer) {
            $now = Carbon::now();
            $carbon_offer_end_time = Carbon::now()->addMinutes($this->inbox_technician_offer_minutes);
            $technician = $next_offer->technician;
            $technician_latitude = $technician->last_latitude;
            $technician_longitude = $technician->last_longitude;
            $ticket_maintenance = $maintenance_result->ticket_maintenance;
            $base = $ticket_maintenance->node->base;
            $province_code = $base?->province_code;
            $base_latitude = $base?->latitude;
            $base_longitude = $base?->longitude;
            $technician_to_base_distance = PredefinedCostHelper::calculateDistance(
                ['latitude' => $technician_latitude, 'longitude' => $technician_longitude], 
                ['latitude' => $base_latitude, 'longitude' => $base_longitude]
            );
            $technician_to_base_nominal = PredefinedCostHelper::calculateTechnicianToBaseNominal($province_code, $technician_to_base_distance, $predefined);

            $next_offer->update([
                'technician_latitude' => $technician_latitude,
                'technician_longitude' => $technician_longitude,
                'technician_to_base_distance' => $technician_to_base_distance,
                'technician_to_base_nominal' => $technician_to_base_nominal,
                'status' => InboxTechnicianStatus::OFFER, 
                'offer_start_time' => $now->timestamp,
                'offer_end_time' => $carbon_offer_end_time->timestamp,
                'offered_at' => $now->timestamp
            ]);

            InboxTechnicianPredefinedLog::create([
                'inbox_technician_id' => $next_offer->id,
                'technician_latitude' => $technician_latitude,
                'technician_longitude' => $technician_longitude,
                'technician_to_base_distance' => $technician_to_base_distance,
                'technician_to_base_nominal' => $technician_to_base_nominal,
                'created_at' => $now->timestamp
            ]);

            dispatch(new InboxJob([
                'inbox_id' => $inbox->id, 
                'technician_id' => $next_offer->technician_id
            ]))
            ->delay($carbon_offer_end_time);
        } else {
            $inbox->update(['status' => InboxStatus::DIDNT_GET_TECHNICIAN]);

            foreach($inbox->inbox_technicians as $value) {
                $value->technician->update(['is_have_inbox' => false]);
            }

            # create inbox predefined 2
            /*if($predefined == 1) {
                $predefined = 2;
                $this->createInbox($maintenance_result, $predefined);
            }*/
        }

        return true;
    }

    # belum di pakai
    /*private function createInbox($maintenance_result, $predefined) {
        $ticket_maintenance = $maintenance_result->ticket_maintenance;
        $node = $ticket_maintenance->node;
        $node_latitude = $node->latitude_verified;
        $node_longitude = $node->longitude_verified;
        $base = $node->base;

        $warning_message = "TM = $ticket_maintenance->no_tiket, Node ID = $node->id";
        if(!$base) {
            Log::warning("Broadcast predefined $predefined | Base dari nodelink tersebut belum terinput | $warning_message");
            return true;
        }
        if(!$node_latitude || !$node_longitude) {
            Log::warning("Broadcast predefined $predefined | Koordinat nodelink belum terinput | $warning_message");
            return true;
        }

        $province_code = $base->province_code;
        $base_latitude = $base->latitude;
        $base_longitude = $base->longitude;

        $technicians = User::selectRaw("
            id, nama_teknisi, last_latitude, last_longitude,
            (
                6371 * ACOS(
                    COS(RADIANS($node_latitude)) * COS(RADIANS(last_latitude)) * COS(
                        RADIANS(last_longitude) - RADIANS($node_longitude)
                    ) + SIN(RADIANS($node_latitude)) * SIN(RADIANS(last_latitude))
                )
            ) AS distance
        ")
        ->whereNotNull('last_latitude')->whereNotNull('last_longitude')
        ->where('is_on_track_location', 1)->where('is_have_inbox', 0)->where('is_aktif', 1)
        ->orderBy('distance', 'ASC')->limit(10)->get();

        $base_to_remote_distance = PredefinedCostHelper::calculateDistance(
            ['latitude' => $base_latitude, 'longitude' => $base_longitude], 
            ['latitude' => $node_latitude, 'longitude' => $node_longitude]
        );

        $inbox = Inbox::create([
            'maintenance_result_id' => $maintenance_result->id,
            'predefined' => $predefined,
            'status' => $technicians->isNotEmpty() ? InboxStatus::LOOKING_TECHNICIAN : InboxStatus::DIDNT_GET_TECHNICIAN,
            'base_to_remote_distance' => $base_to_remote_distance,
            'packet_nominal' => PredefinedCostHelper::getPacketNominal($ticket_maintenance->kd_product_id, $base_to_remote_distance),
            'base_to_remote_nominal' => PredefinedCostHelper::getBaseToRemoteNominal($ticket_maintenance->district_code, $base_to_remote_distance)
        ]);
        $inbox_id = $inbox->id;

        if($technicians->isNotEmpty()) {
            $array_technicians = [];
            foreach($technicians as $technician) {
                $technician_id = $technician->id;
                $technician_latitude = $technician->last_latitude;
                $technician_longitude = $technician->last_longitude;
                
                $technician_to_base_distance = PredefinedCostHelper::calculateDistance(
                    ['latitude' => $technician_latitude, 'longitude' => $technician_longitude], 
                    ['latitude' => $base_latitude, 'longitude' => $base_longitude]
                );

                $array_technicians[] = [
                    'inbox_id' => $inbox_id,
                    'technician_id' => $technician_id,
                    'technician_latitude' => $technician_latitude,
                    'technician_longitude' => $technician_longitude,
                    'technician_to_base_distance' => $technician_to_base_distance,
                    'technician_to_base_nominal' => PredefinedCostHelper::calculateTechnicianToBaseNominal($province_code, $technician_to_base_distance, $predefined)
                ];

                $technician->update(['is_have_inbox' => 1]);
            }
            usort($array_technicians, function($a, $b) {
                return $a['technician_to_base_nominal'] - $b['technician_to_base_nominal'];
            });
            foreach($array_technicians as $key => $array_technician) {
                $now = Carbon::now();
                $carbon_offer_end_time = Carbon::now()->addMinutes($this->inbox_technician_offer_minutes);
                $offer_sequence = $key + 1;

                $array_technician['uuid'] = Str::uuid();
                $array_technician['status'] = $offer_sequence == 1 ? InboxTechnicianStatus::OFFER : InboxTechnicianStatus::WAITING;
                $array_technician['offer_sequence'] = $offer_sequence;
                $array_technician['offer_start_time'] = $offer_sequence == 1 ? $now->timestamp : null;
                $array_technician['offer_end_time'] = $offer_sequence == 1 ? $carbon_offer_end_time->timestamp : null;
                $array_technician['waiting_at'] = $now->timestamp;
                $array_technician['offered_at'] = $offer_sequence == 1 ? $now->timestamp : null;
                InboxTechnician::create($array_technician);
            }
        }

        $maintenance_result->update(['last_inbox_id' => $inbox_id]);

        return true;
    }*/

    private function getInboxTechnicianByOfferSequence($inbox_id, $offer_sequence) {
        return InboxTechnician::with('technician')->where('inbox_id', $inbox_id)
        ->where('offer_sequence', $offer_sequence)->first();
    }
}