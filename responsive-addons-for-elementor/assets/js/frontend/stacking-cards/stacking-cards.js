function initStackingCards($scope) {
  const cards = gsap.utils.toArray($scope.find(".rael-stacking-card"));

  cards.forEach((card, index) => {
    const gapValue = card.dataset.gap || "50px";
    const gapMatch = gapValue.match(/^([0-9.]+)([a-z%]*)$/);
    const gapNum = gapMatch ? parseFloat(gapMatch[1]) : 0;
    const gapUnit = gapMatch ? gapMatch[2] : "px";

    const baseX = parseFloat(card.dataset.translateX) || 0;
    const baseY = parseFloat(card.dataset.translateY) || 0;

    const baseRotate = parseFloat(card.dataset.rotate) || 0;
    const scrollRotate = parseFloat(card.dataset.scrollrotate) || 0;

    const baseScale = parseFloat(card.dataset.scale) || 1;
    const baseBlur = parseFloat(card.dataset.blur) || 0;

    const baseGreyscale = parseFloat(card.dataset.greyscale) || 0;
    const scrollGreyscale = parseFloat(card.dataset.scrollgreyscale) || 0;

    //  Set initial state based on dataset values
    gsap.set(card, {
      x: baseX,
      rotate: baseRotate,
      scale: baseScale,
      filter: `blur(0px) grayscale(${baseGreyscale}%)`,
      opacity: 1,
    });

    ScrollTrigger.create({
      trigger: card,
      start: "bottom top",
      onEnter: () => {
        gsap.to(card, {
          x: baseX,
          rotate: scrollRotate,
          scale: baseScale,
          filter: `blur(${baseBlur}px) grayscale(${scrollGreyscale}%)`,
          opacity: 1,
          overwrite: "auto",
          duration: 0.4,
          ease: "power2.out",
        });
      },
      onLeaveBack: () => {
        gsap.to(card, {
          x: baseX,
          rotate: baseRotate,
          scale: baseScale,
          filter: `blur(0px) grayscale(${baseGreyscale}%)`,
          opacity: 1,
          overwrite: "auto",
          duration: 0.4,
          ease: "power2.out",
        });
      },
    });
  });
}

// Elementor frontend + editor support
jQuery(window).on("elementor/frontend/init", function () {
  // Frontend and editor hook
  if (
    typeof elementorFrontend !== "undefined" &&
    typeof elementorFrontend.hooks !== "undefined"
  ) {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/rael-stacking-cards.default",
      function ($scope) {
        // ===== Add dynamic CSS variables to parent =====
        const wrapper = $scope.find(".rael-stacking-cards-wrapper");
        const parent = wrapper.closest(".elementor-widget-rael-stacking-cards");

        if (parent.length) {
          const cardHeight = wrapper.data("card-height");
          const cardOffset = wrapper.data("card-offset");

          parent[0].style.setProperty("--card-height", cardHeight);
          parent[0].style.setProperty("--card-top-offset", cardOffset);
        }
        // ===== End dynamic CSS variables =====
        initStackingCards($scope);
      }
    );
  }

  // Editor live preview
  if (elementorFrontend.isEditMode()) {
    jQuery(window).on("load", function () {
      jQuery(".rael-stacking-cards").each(function () {
        const $scope = jQuery(this);
        const wrapper = $scope.find(".rael-stacking-cards-wrapper");
        const parent = wrapper.closest(".elementor-widget-rael-stacking-cards");

        if (parent.length) {
          const cardHeight = wrapper.data("card-height");
          const cardOffset = wrapper.data("card-offset");

          parent[0].style.setProperty("--card-height", cardHeight);
          parent[0].style.setProperty("--card-top-offset", cardOffset);
        }
        initStackingCards(jQuery(this));
      });
    });
  }
});
