(function (Drupal) {
  Drupal.behaviors.drupifyHomepage = {
    attach(context) {
      once("drupify-home-menu", ".drupify-menu-toggle", context).forEach((menuButton) => {
        const nav = document.getElementById(menuButton.getAttribute("aria-controls"));
        if (!nav) {
          return;
        }

        menuButton.addEventListener("click", () => {
          const isOpen = menuButton.getAttribute("aria-expanded") === "true";
          menuButton.setAttribute("aria-expanded", String(!isOpen));
          nav.classList.toggle("is-open", !isOpen);
          document.body.classList.toggle("drupify-nav-open", !isOpen);
        });

        nav.addEventListener("click", (event) => {
          if (event.target instanceof HTMLAnchorElement) {
            menuButton.setAttribute("aria-expanded", "false");
            nav.classList.remove("is-open");
            document.body.classList.remove("drupify-nav-open");
          }
        });
      });

      once("drupify-home-faq", ".drupify-faq-item button", context).forEach((button) => {
        button.addEventListener("click", () => {
          const panel = button.nextElementSibling;
          const isOpen = button.getAttribute("aria-expanded") === "true";
          button.setAttribute("aria-expanded", String(!isOpen));
          if (panel) {
            panel.hidden = isOpen;
          }
        });
      });
    },
  };
})(Drupal);
