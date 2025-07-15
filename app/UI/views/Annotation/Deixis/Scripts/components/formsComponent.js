function formsComponent() {
    return {
        formsPane: null,
        init() {
            this.formsPane = document.getElementById("formsPane");
            document.addEventListener("action-play", (e) => {
                this.disableTabs();
            });

            document.addEventListener("action-pause", (e) => {
                this.enableTabs();
            });
        },

        toggleTabs(disabled = true) {
            const tabs= document.querySelectorAll("#formsPane .item");
            tabs.forEach(tab => {
                tab.disabled = disabled;
                if (disabled) {
                    tab.classList.add("disabled");
                } else {
                    tab.classList.remove("disabled");
                }
            });
        },

        disableTabs() {
            this.toggleTabs(true);
        },

        enableTabs() {
            this.toggleTabs(false);
        },
    };
}
