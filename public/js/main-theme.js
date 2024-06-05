function data() {
    function getThemeFromLocalStorage() {
        // if user already changed the theme, use it
        if (window.localStorage.getItem('dark')) {
            return JSON.parse(window.localStorage.getItem('dark'))
        }
  
        // else return their preferences
        return (
            !!window.matchMedia &&
            window.matchMedia('(prefers-color-scheme: dark)').matches
        )
    }
  
    function setThemeToLocalStorage(value) {
        window.localStorage.setItem('dark', value)
    }
  
    return {
        dark: getThemeFromLocalStorage(),
        toggleTheme() {
            this.dark = !this.dark
            setThemeToLocalStorage(this.dark)
        },
        isSideMenuOpen: false,
        toggleSideMenu() {
            this.isSideMenuOpen = !this.isSideMenuOpen
        },
        closeSideMenu() {
            this.isSideMenuOpen = false
        },
        isSelectRoleMenuOpen: false,
        toggleSelectRoleMenu() {
            this.isSelectRoleMenuOpen = !this.isSelectRoleMenuOpen
        },
        closeSelectRoleMenu() {
            this.isSelectRoleMenuOpen = false
        },
        isProfileMenuOpen: false,
        toggleProfileMenu() {
            this.isProfileMenuOpen = !this.isProfileMenuOpen
        },
        closeProfileMenu() {
            this.isProfileMenuOpen = false
        },
        isUserMenuOpen: false,
        toggleUserMenu() {
            this.isUserMenuOpen = !this.isUserMenuOpen
        },
        // Modal
        isModalOpen: false,
        trapCleanup: null,
        openModal() {
            this.isModalOpen = true
            this.trapCleanup = focusTrap(document.querySelector('#modal'))
        },
        closeModal() {
            this.isModalOpen = false
            this.trapCleanup()
        },
    }
}

selectRole = () => ({
    set(event) {
        let role_id = event.currentTarget.dataset.role_id

        fetch('/user/select-role', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.$refs.csrf_token.content
            },
            body: JSON.stringify({role_id: role_id})
        })
        .then(response => {
            if(response.status != 200) {
                return Promise.reject(response)
            }
            location.href = '/'
        })
        .catch(error => {
            let statusText = error.statusText
        })
    }
})