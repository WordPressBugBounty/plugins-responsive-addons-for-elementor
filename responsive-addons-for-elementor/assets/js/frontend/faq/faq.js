jQuery(window).on("elementor/frontend/init", function() {

    elementorFrontend.hooks.addAction('frontend/element_ready/rael-faq.default', function ($scope, $) {
        var $this;
        var rael_faq_answer = $scope.find('.rael-faq-accordion > .rael-accordion-content');
        var layout = $scope.find( '.rael-faq-container' );
        var faq_layout = layout.data('layout');
        $scope.find('.rael-accordion-title').on( 'click keypress',
            function(e){
                e.preventDefault();
                $this = $(this);
                rael_accordion_activate_deactivate($this);
            }
        );
        function rael_accordion_activate_deactivate($this) {
            if('toggle' == faq_layout ) {
                if( $this.hasClass('rael-title-active') ){
                    $this.removeClass('rael-title-active');
                    $this.attr('aria-expanded', 'false');
                }
                else{
                    $this.addClass('rael-title-active');
                    $this.attr('aria-expanded', 'true');
                }
                $this.next('.rael-accordion-content').slideToggle( 'normal','swing');
            }
            else if( 'accordion' == faq_layout ){
                if( $this.hasClass('rael-title-active') ){
                    $this.removeClass( 'rael-title-active');
                    $this.next('.rael-accordion-content').slideUp( 'normal','swing',
                        function(){
                            $(this).prev().removeClass('rael-title-active');
                            $this.attr('aria-expanded', 'false');
                        });
                } else {
                    if( rael_faq_answer.hasClass('rael-title-active') ){
                        rael_faq_answer.removeClass('rael-title-active');
                    }
                    rael_faq_answer.slideUp('normal','swing' , function(){
                        $(this).prev().removeClass('rael-title-active');
                    });

                    $this.addClass('rael-title-active');
                    $this.attr('aria-expanded', 'true');
                    $this.next('.rael-accordion-content').slideDown('normal','swing');
                }
                return false;
            }
        }
    });
});