/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the new BSD license.
 *
 * @author      DragonOfMercy <dragonofmercy@gmail.com>
 * @license     http://www.opensource.org/licenses/BSD-3-Clause New BSD license
 * @version     1.3
 */
(function($, scrollable){

    // Data storage constant
    var DATA = 'scrollable';

    /**
     * Get scrollable object
     *
     * @param {Object} options
     * @param {jQuery} el
     * @returns {$.scrollable}
     */
    var getScrollableObject = function(options, el){
        if(!el.data(DATA)){
            var obj = new $.scrollable($.extend({}, $.fn.scrollable.defaults, options));
            el.data(DATA, obj);
            obj.init(el);
        }
        return el.data(DATA);
    };

    $.scrollable = function(opts){
        if(opts == undefined){
            opts = $.fn.scrollable.defaults;
        }
        this.options = opts;
    };

    $.extend($.scrollable.prototype, {
        init: function(element){
            this.element = element.css('overflow', 'hidden');
            this.element.addClass('scrollable');
            this.element.wrapInner('<div class="scrollable-area" />');
            this.element.wrapInner('<div class="scrollable-container" />');
            this.scrollableArea = this.element.find('.scrollable-area:eq(0)');
            this.scrollableContainer = this.element.find('.scrollable-container:eq(0)');
            this.scrolling = { x: { container: null, handle: null }, y: { container: null, handle: null } };
            this.updateContainer();
            this.lastWidth = this.getAreaSize('width');
            this.lastHeight = this.getAreaSize('height');
            var $this = this;
            $(window).on('resize', function(){
                $this.updateContainer();
                $this.updateArea();
            });
            this.element.on('update', function(){
                $this.updateContainer();
                $this.updateArea();
                $this.options.onUpdate();
            });
            if(this.options.autoHide){
                this.element.addClass('autohide');
            }
            this.element.on('mouseover', function(){
                $this.element.addClass('hover');
            });
            this.element.on('mouseout', function(){
                $this.element.removeClass('hover');
            });
            setInterval(function(){
                if($this.lastWidth != $this.getAreaSize('width') || $this.lastHeight != $this.getAreaSize('height')){
                    $this.element.trigger('update');
                    $this.lastWidth = $this.getAreaSize('width');
                    $this.lastHeight = $this.getAreaSize('height');
                }
            }, 500);
            if(this.options.mouseWheel){
                this.element.on('mousewheel', function(e){
                    var deltaFactor = e.deltaFactor;
                    if(deltaFactor >= $this.options.mouseWheelMaxDelta){
                        deltaFactor = $this.options.mouseWheelMaxDelta;
                    }
                    if(e.deltaY != 0 && $this.scrolling.y.handle != null){
                        if($this.getOffset('top', $this.scrolling.y.handle) >= 0){
                            if($this.options.transferScrolling !== true){
                                e.preventDefault();
                            }
                            var new_top = $this.getOffset('top', $this.scrolling.y.handle) + ((e.deltaY * deltaFactor) * -1);
                            new_top = (new_top <= 0 ? 0 : new_top);
                            new_top = (new_top + $this.scrolling.y.handle.height() >= $this.scrolling.y.container.height() ? $this.scrolling.y.container.height() - $this.scrolling.y.handle.height() : new_top);
                            if(new_top != $this.getOffset('top', $this.scrolling.y.handle)){
                                e.preventDefault();
                                e.stopImmediatePropagation();
                            }
                            $this.scrolling.y.handle.css('top', new_top);
                            $this.moveArea({ top: new_top }, 'y');
                        }
                    }
                    if(e.deltaX != 0 && $this.scrolling.x.handle != null){
                        if($this.getOffset('left', $this.scrolling.x.handle) >= 0){
                            if($this.options.transferScrolling !== true){
                                e.preventDefault();
                            }
                            e.preventDefault();
                            var new_left = $this.getOffset('left', $this.scrolling.x.handle) + (e.deltaX * deltaFactor);
                            new_left = (new_left <= 0 ? 0 : new_left);
                            new_left = (new_left + $this.scrolling.x.handle.width() >= $this.scrolling.x.container.width() ? $this.scrolling.x.container.width() - $this.scrolling.x.handle.width() : new_left);
                            if(new_left != $this.getOffset('left', $this.scrolling.y.handle)){
                                e.preventDefault();
                                e.stopImmediatePropagation();
                            }
                            $this.scrolling.x.handle.css('left', new_left);
                            $this.moveArea({ left: new_left }, 'x');
                        }
                    }
                });
            }
        },
        getOffset: function(direction, el){
            if(el.css(direction) == 'auto'){
                return 0;
            } else {
                return parseInt(el.css(direction));
            }
        },
        getAreaSize: function(direction){
            if(direction == 'width'){
                return this.scrollableArea.prop('scrollWidth');
            } else {
                return this.scrollableArea.prop('scrollHeight');
            }
        },
        updateScrollbars: function(){
            if(this.scrollableContainer.width() < this.getAreaSize('width')){
                if(this.scrolling.x.container == null){
                    this.drawScrollbar('x');
                }
            } else {
                if(this.scrolling.x.container != null){
                    this.scrolling.x.container.parent().remove();
                    this.scrolling.x.handle = null;
                    this.scrolling.x.container = null;
                }
            }
            if(this.scrollableContainer.height() < this.scrollableArea.height()){
                if(this.scrolling.y.container == null){
                    this.drawScrollbar('y');
                }
            } else {
                if(this.scrolling.y.container != null){
                    this.scrolling.y.container.parent().remove();
                    this.scrolling.y.handle = null;
                    this.scrolling.y.container = null;
                }
            }
            this.updateHandleSize();
        },
        updateContainer: function(){
            if(this.element.is('body')){
                this.scrollableContainer.width($(window).width());
                this.scrollableContainer.height($(window).height());
            } else {
                if(!this.element.height())
                {
                    this.element.height(this.scrollableArea.height());
                }
                if(!this.element.width())
                {
                    this.element.width('100%');
                }
                this.scrollableContainer.width(this.element.width());
                this.scrollableContainer.height(this.element.height());
            }
            this.updateScrollbars();
        },
        drawScrollbar: function(axis){
            var $this = this;
            this.scrolling[axis].handle = $('<span />').addClass('handle').hover(function(){ $(this).addClass('hover'); }, function(){ $(this).removeClass('hover'); });
            this.scrolling[axis].container = $('<span />').addClass('handle-container').append(this.scrolling[axis].handle);
            this.element.prepend($('<span />').addClass('scrollbar').addClass('scrollbar-' + axis).append(this.scrolling[axis].container));
            $('.scrollbar-' + axis + ' .handle-container .handle', this.element).draggable({
                axis: axis,
                containment: 'parent',
                start: function(){
                    $this.element.addClass('scrolling');
                },
                stop: function(){
                    $this.element.removeClass('scrolling');
                },
                drag: function(e, ui){
                    $this.moveArea(ui.position, axis);
                }
            });
        },
        updateHandleSize: function(){
            if(this.scrolling.x.container != null && this.scrolling.y.container != null){
                this.scrolling.x.container.parent().addClass('corner');
                this.scrolling.y.container.parent().addClass('corner');
            } else {
                if(this.scrolling.x.container != null){
                    this.scrolling.x.container.parent().removeClass('corner');
                }
                if(this.scrolling.y.container != null){
                    this.scrolling.y.container.parent().removeClass('corner');
                }
            }
            var ratios = [
                this.scrollableContainer.width() / this.getAreaSize('width'),
                this.scrollableContainer.height() / this.getAreaSize('height')
            ];
            if(this.scrolling.x.container != null){
                this.scrolling.x.handle.width(Math.round(this.scrolling.x.container.width() * ratios[0]));
            }
            if(this.scrolling.y.container != null){
                this.scrolling.y.handle.height(Math.round(this.scrolling.y.container.height() * ratios[1]));
            }
        },
        moveArea: function(offset, axis){
            var ratio = 0;
            if(axis == 'x'){
                ratio = (this.getAreaSize('width') - this.scrollableContainer.width()) / (this.scrolling.x.container.width() - this.scrolling.x.handle.width());
                this.scrollableArea.css('left', Math.round((offset.left * ratio) * -1));
            } else {
                ratio = (this.getAreaSize('height') - this.scrollableContainer.height()) / (this.scrolling.y.container.height() - this.scrolling.y.handle.height());
                this.scrollableArea.css('top', Math.round((offset.top * ratio) * -1));
            }
        },
        updateArea: function(){
            if(this.scrolling.y.container != null){
                var percent_y = Math.round((this.getOffset('top', this.scrollableArea) / (this.scrollableContainer.height() - this.getAreaSize('height'))) * 100);
                percent_y = percent_y >= 100 ? 100 : percent_y;
                var new_top = Math.round((this.scrolling.y.container.height() - this.scrolling.y.handle.height()) * percent_y / 100);
                this.scrolling.y.handle.css('top', new_top);
                if(percent_y == 100){
                    this.moveArea({ top: new_top }, 'y');
                }
            } else {
                this.scrollableArea.css('top', 0);
            }
            if(this.scrolling.x.container != null){
                var percent_x = Math.round((this.getOffset('left', this.scrollableArea) / (this.scrollableContainer.width() - this.getAreaSize('width'))) * 100);
                percent_x = percent_x >= 100 ? 100 : percent_x;
                var new_left = Math.round((this.scrolling.x.container.width() - this.scrolling.x.handle.width()) * percent_x / 100);
                this.scrolling.x.handle.css('left', new_left);
                if(percent_x == 100){
                    this.moveArea({ left: new_left }, 'x');
                }
            } else {
                this.scrollableArea.css('left', 0);
            }
        },
        moveX: function(v){
            if(this.scrolling.x.handle != null){
                v = (v + this.scrolling.x.handle.width() >= this.scrolling.x.container.width() ? this.scrolling.x.container.width() - this.scrolling.x.handle.width() : v);
                this.moveArea({left: v}, 'x');
                this.scrolling.x.handle.css('left', v);
            }
            return this;
        },
        moveY: function(v){
            if(this.scrolling.y.handle != null){
                v = (v + this.scrolling.y.handle.height() >= this.scrolling.y.container.height() ? this.scrolling.y.container.height() - this.scrolling.y.handle.height() : v);
                this.moveArea({top: v}, 'y');
                this.scrolling.y.handle.css('top', v);
            }
            return this;
        }
    });

    /**
     * Scrollable plugin
     *
     * @param {Object|undefined} [options]
     * @returns {$.scrollable|Array}
     */
    $.fn.scrollable = function(options){
        options = options || {};
        var selector = this.filter('select');
        if(selector.length > 1){
            var objects = [];
            selector.each(function(){
                objects.push(getScrollableObject(options, $(this)));
            });
            return objects;
        } else {
            return getScrollableObject(options, $(this));
        }
    };

    $.fn.scrollable.defaults = {
        onUpdate: function(){},
        mouseWheel: true,
        mouseWheelMaxDelta: 30,
        autoHide: false,
        transferScrolling: true
    };

})(jQuery, 'scrollable');
