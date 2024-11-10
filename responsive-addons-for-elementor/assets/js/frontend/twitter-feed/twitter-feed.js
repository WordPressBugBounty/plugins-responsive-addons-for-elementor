var RAELWidgetTwitterFeedHandler = function ($scope, $) {
  if ("undefined" == typeof $scope) {
    return;
  }

  var gutter = $(".rael-twitter-feed--masonry", $scope).data("gutter");
  var options = {
    itemSelector: ".rael-twitter-feed__item",
    percentPosition: true,
    masonry: {
      columnWidth: ".rael-twitter-feed__item",
      gutter: gutter,
    },
  };

  var twitter_feed = $(".rael-twitter-feed--masonry", $scope).isotope(options);

  twitter_feed.imagesLoaded().progress(function () {
    twitter_feed.isotope("layout");
  });
};

jQuery(window).on("elementor/frontend/init", function () {
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/rael-twitter-feed.default",
    RAELWidgetTwitterFeedHandler
  );
});
