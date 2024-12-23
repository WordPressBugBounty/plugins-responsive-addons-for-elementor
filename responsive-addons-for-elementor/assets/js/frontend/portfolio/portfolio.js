class RaelPortfolioHandler extends elementorModules.frontend.handlers.Base {
    getSkinPrefix() {
        return '';
    }

    getDefaultSettings() {
        var settings = {
            classes: {
                fitHeight: 'elementor-fit-height',
                hasItemRatio: 'elementor-has-item-ratio',
                active: 'elementor-active',
                item: 'responsive-portfolio-item',
                ghostItem: 'responsive-portfolio-ghost-item'
            },
            selectors: {
                postsContainer: '.responsive-posts-container',
                post: '.elementor-post',
                postThumbnail: '.elementor-post__thumbnail',
                postThumbnailImage: '.elementor-post__thumbnail img'
            }
        };

        settings.transitionDuration = 450;

        return settings;
    }

    getDefaultElements() {
        var selectors = this.getSettings('selectors');
        return {
            $postsContainer: this.$element.find(selectors.postsContainer),
            $posts: this.$element.find(selectors.post),
            $filterButtons: this.$element.find('.responsive-portfolio__filter'),
        };
    }

    getOffset(itemIndex, itemWidth, itemHeight) {
        var settings = this.getSettings(), itemGap = this.elements.$postsContainer.width() / settings.colsCount - itemWidth;
        itemGap += itemGap / (settings.colsCount - 1);
        return {
            start: (itemWidth + itemGap) * (itemIndex % settings.colsCount),
            top: (itemHeight + itemGap) * Math.floor(itemIndex / settings.colsCount)
        };
    }

    getClosureMethodsNames() {
        return elementorModules.frontend.handlers.Base.prototype.getClosureMethodsNames.apply(this, arguments).concat(['fitImages', 'onWindowResize', 'runMasonry', 'onFilterButtonClick']);
    }

    filterItems(term) {
        var $posts = this.elements.$posts, activeClass = this.getSettings('classes.active'), termSelector = '.elementor-filter-' + term;
        if ('__all' === term) {
            $posts.addClass(activeClass);
            return;
        }
        $posts.not(termSelector).removeClass(activeClass);
        $posts.filter(termSelector).addClass(activeClass);
    }

    removeExtraGhostItems() {
        var settings = this.getSettings(), $shownItems = this.elements.$posts.filter(':visible'), emptyColumns = (settings.colsCount - $shownItems.length % settings.colsCount) % settings.colsCount, $ghostItems = this.elements.$postsContainer.find('.' + settings.classes.ghostItem);
        $ghostItems.slice(emptyColumns).remove();
    }

    handleEmptyColumns() {
        this.removeExtraGhostItems();
        var settings = this.getSettings(), $shownItems = this.elements.$posts.filter(':visible'), $ghostItems = this.elements.$postsContainer.find('.' + settings.classes.ghostItem), emptyColumns = (settings.colsCount - ($shownItems.length + $ghostItems.length) % settings.colsCount) % settings.colsCount;
        for (var i = 0; i < emptyColumns; i++) {
            this.elements.$postsContainer.append(jQuery('<div>', { class: settings.classes.item + ' ' + settings.classes.ghostItem }));
        }
    }

    showItems($activeHiddenItems) {
        $activeHiddenItems.show();
        setTimeout(function () {
            $activeHiddenItems.css({ opacity: 1 });
        });
    }

    hideItems($inactiveShownItems) {
        $inactiveShownItems.hide();
    }

    arrangeGrid() {
        var $ = jQuery, self = this, settings = self.getSettings(), $activeItems = self.elements.$posts.filter('.' + settings.classes.active), $inactiveItems = self.elements.$posts.not('.' + settings.classes.active), $shownItems = self.elements.$posts.filter(':visible'), $activeOrShownItems = $activeItems.add($shownItems), $activeShownItems = $activeItems.filter(':visible'), $activeHiddenItems = $activeItems.filter(':hidden'), $inactiveShownItems = $inactiveItems.filter(':visible'), itemWidth = $shownItems.outerWidth(), itemHeight = $shownItems.outerHeight();
        self.elements.$posts.css('transition-duration', settings.transitionDuration + 'ms');
        self.showItems($activeHiddenItems);
        if (self.isEdit) {
            self.fitImages();
        }
        self.handleEmptyColumns();
        if (self.isMasonryEnabled()) {
            self.hideItems($inactiveShownItems);
            self.showItems($activeHiddenItems);
            self.handleEmptyColumns();
            self.runMasonry();
            return;
        }
        $inactiveShownItems.css({
            opacity: 0,
            transform: 'scale3d(0.2, 0.2, 1)'
        });
        $activeShownItems.each(function () {
            var $item = $(this), currentOffset = self.getOffset($activeOrShownItems.index($item), itemWidth, itemHeight), requiredOffset = self.getOffset($shownItems.index($item), itemWidth, itemHeight);
            if (currentOffset.start === requiredOffset.start && currentOffset.top === requiredOffset.top) {
                return;
            }
            requiredOffset.start -= currentOffset.start;
            requiredOffset.top -= currentOffset.top;
            if (elementorFrontend.config.is_rtl) {
                requiredOffset.start *= -1;
            }
            $item.css({
                transitionDuration: '',
                transform: 'translate3d(' + requiredOffset.start + 'px, ' + requiredOffset.top + 'px, 0)'
            });
        });
        setTimeout(function () {
            $activeItems.each(function () {
                var $item = $(this), currentOffset = self.getOffset($activeOrShownItems.index($item), itemWidth, itemHeight), requiredOffset = self.getOffset($activeItems.index($item), itemWidth, itemHeight);
                $item.css({ transitionDuration: settings.transitionDuration + 'ms' });
                requiredOffset.start -= currentOffset.start;
                requiredOffset.top -= currentOffset.top;
                if (elementorFrontend.config.is_rtl) {
                    requiredOffset.start *= -1;
                }
                setTimeout(function () {
                    $item.css('transform', 'translate3d(' + requiredOffset.start + 'px, ' + requiredOffset.top + 'px, 0)');
                });
            });
        });
        setTimeout(function () {
            self.hideItems($inactiveShownItems);
            $activeItems.css({
                transitionDuration: '',
                transform: 'translate3d(0px, 0px, 0px)'
            });
            self.handleEmptyColumns();
        }, settings.transitionDuration);
    }

    activeFilterButton(filter) {
        var activeClass = this.getSettings('classes.active'), $filterButtons = this.elements.$filterButtons, $button = $filterButtons.filter('[data-filter="' + filter + '"]');
        $filterButtons.removeClass(activeClass);
        $button.addClass(activeClass);
    }

    setFilter(filter) {
        this.activeFilterButton(filter);
        this.filterItems(filter);
        this.arrangeGrid();
    }

    refreshGrid() {
        this.setColsCountSettings();
        this.arrangeGrid();
    }

    bindEvents() {
        var cid = this.getModelCID();
        var scope = this;
        elementorFrontend.addListenerOnce(cid, 'resize',function () {
            scope.onWindowResize();
        })

        this.elements.$filterButtons.on( 'click', this.onFilterButtonClick.bind( this ) );
    }

    isMasonryEnabled() {
        return !!this.getElementSettings('masonry');
    }

    run() {
        this.fitImages();
        this.initMasonry();
        this.setColsCountSettings();
        this.setFilter('__all');
        this.handleEmptyColumns();
    }

    onInit(...args) {
        elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
        this.bindEvents();
        this.run();
    }

    onFilterButtonClick(event) {
        this.setFilter(jQuery(event.currentTarget).data('filter'));
    }

    onWindowResize() {
        this.fitImages();
        this.runMasonry();
        this.refreshGrid();
    }

    fitImage($post) {
        var settings = this.getSettings(),
            $imageParent = $post.find(settings.selectors.postThumbnail),
            $image = $imageParent.find('img'),
            image = $image[0];

        if (!image) {
            return;
        }

        var imageParentRatio = $imageParent.outerHeight() / $imageParent.outerWidth(),
            imageRatio = image.naturalHeight / image.naturalWidth;
        $imageParent.toggleClass(settings.classes.fitHeight, imageRatio < imageParentRatio);
    }

    fitImages() {
        var $ = jQuery,
            self = this,
            itemRatio = getComputedStyle(this.$element[0], ':after').content,
            settings = this.getSettings();
        this.elements.$postsContainer.toggleClass(settings.classes.hasItemRatio, !!itemRatio.match(/\d/));

        if (self.isMasonryEnabled()) {
            return;
        }

        this.elements.$posts.each(function () {
            var $post = $(this),
                $image = $post.find(settings.selectors.postThumbnailImage);
            self.fitImage($post);
            $image.on('load', function () {
                self.fitImage($post);
            });
        });
    }

    runMasonry($scope) {

        var elements = this.elements;
        elements.$posts.css({
            marginTop: '',
            transitionDuration: ''
        });
        this.setColsCountSettings();
        var colsCount = this.getSettings('colsCount'),
            hasMasonry = this.isMasonryEnabled() && colsCount >= 2;
        elements.$postsContainer.toggleClass('elementor-posts-masonry', hasMasonry);

        if (!hasMasonry) {
            elements.$postsContainer.height('');
            return;
        }
        /* The `verticalSpaceBetween` variable is setup in a way that supports older versions of the portfolio widget */


        var verticalSpaceBetween = this.getElementSettings(this.getSkinPrefix() + 'row_gap.size');

        if ('' === this.getSkinPrefix() && '' === verticalSpaceBetween) {
            verticalSpaceBetween = this.getElementSettings(this.getSkinPrefix() + 'item_gap.size');
        }

        var masonry = new elementorModules.utils.Masonry({
            container: elements.$postsContainer,
            items: elements.$posts.filter(':visible'),
            columnsCount: this.getSettings('colsCount'),
            verticalSpaceBetween: verticalSpaceBetween
        });
        masonry.run();
    }

    setColsCountSettings() {
        var currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
            settings = this.getElementSettings(),
            skinPrefix = this.getSkinPrefix(),
            colsCount;

        switch (currentDeviceMode) {
            case 'mobile':
                colsCount = settings[skinPrefix + 'columns_mobile'];
                break;

            case 'tablet':
                colsCount = settings[skinPrefix + 'columns_tablet'];
                break;

            default:
                colsCount = settings[skinPrefix + 'columns'];
        }

        this.setSettings('colsCount', colsCount);
    }

    onElementChange(propertyName) {
        this.fitImages();
        this.runMasonry();
        if ('classic_item_ratio' === propertyName) {
            this.refreshGrid();
        }
    }

    initMasonry() {
        var $scope = this;

        imagesLoaded(this.elements.$posts, this.runMasonry($scope));
    }
}


jQuery(window).on("elementor/frontend/init", () => {

    const addHandler = ($element) => {
        elementorFrontend.elementsHandler.addHandler(RaelPortfolioHandler, {
            $element,
        });
    };
    elementorFrontend.hooks.addAction("frontend/element_ready/rael-portfolio.default", addHandler);

});