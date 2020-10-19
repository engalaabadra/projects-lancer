(function() {

                /* matchHeight example */

                $(function() {
                    // apply your matchHeight on DOM ready (they will be automatically re-applied on load or resize)

                    // get test settings
                    var byRow = $('body').hasClass('test-rows');

                    // apply matchHeight to each item container's items
                    $('.items-container').each(function() {
                        $(this).children('.item').matchHeight({
                            byRow: byRow
                        });
                    });

                    // test property
                    $('.property-items .item').matchHeight({
                        property: 'min-height'
                    });

                    // test target
                    $('.target-items').each(function() {
                        $(this).children('.item-0, .item-2, .item-3').matchHeight({
                            target: $(this).find('.item-1')
                        });
                    });

                    // example of removing matchHeight
                    $('.btn-remove').click(function() {
                        $('.item').matchHeight({ 
                            remove: true 
                        });
                    });

                    // button to show hidden elements
                    $('.btn-hidden').click(function() {
                        $('body').removeClass('test-hidden');
                    });

                    // example of update callbacks (uncomment to test)
                    $.fn.matchHeight._beforeUpdate = function(event, groups) {
                        //var eventType = event ? event.type + ' event, ' : '';
                        //console.log("beforeUpdate, " + eventType + groups.length + " groups");
                    }

                    $.fn.matchHeight._afterUpdate = function(event, groups) {
                        //var eventType = event ? event.type + ' event, ' : '';
                        //console.log("afterUpdate, " + eventType + groups.length + " groups");
                    }
                });

            })();