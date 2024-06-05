import Alpine from 'alpinejs'
import Jquery from 'jquery'
import DataTables from 'datatables.net'
import select2 from 'select2'
import sweetalert2 from 'sweetalert2'
import LoadingOverlay from 'gasparesganga-jquery-loading-overlay'

window.Alpine = Alpine
Alpine.start()
select2(Jquery)
window.$ = Jquery
window.Swal = sweetalert2