$.fn.kCheckbox = function(options) {
        
        var defaults = {
            class:          'k-checkbox',
            vChecked:       1,
            vUnchecked:     0,
            vGrayed:        -1,
            onChecked:      false,
            onUnchecked:    false,
            onGrayed:       false
        };
        
        if(options !== undefined) {
            $.each(options, function(index, value) {
                defaults[index] = value;
            });
        }
        
        var $els = $(this);
        
        $els.each(function() {
            var $el = $(this);
            if($el[0].nodeName != 'INPUT') {
                    return;
            }
            $el.hide();
            $.each(defaults, function(index, value) {
                $el[index] = value;
            });
            var $parent = $el.parent();
            var el_cl = $el.attr('class');
            var $cb = $('<span class="k-checkbox ' + el_cl + '"></span>');
            $el.after($cb);
            var $clickEl = $cb;
            if($parent[0].nodeName == 'LABEL') {
                $clickEl = $parent;
            }
            var val = $el.val();
            if(val != $el.vChecked && val != $el.vUnchecked && val != $el.vGrayed) {
                $el.val($el.vUnchecked);
            }
            $clickEl.addClass('k-clickable');
            
            // клик на элементе
            $clickEl.on('click', function() {
                var val = $el.val();
                if(val == $el.vChecked) {
                    val = $el.vGrayed;
                } else {
                    if(val == $el.vGrayed) {
                        val = $el.vUnchecked;
                    } else {
                        val = $el.vChecked;
                    }
                }
                $el.val(val);
                $el.trigger('change');
                $el.trigger('refresh');
            });
            
            // обновление при динамическом изменении
            $el.on('refresh', function() {
                var val = $el.val();
                var cl = 'vUnchecked';
                if(val == $el.vChecked) {
                    cl = 'vChecked';
                } else {
                    if(val == $el.vGrayed) {
                        cl = 'vGrayed';
                    }
                }
                $cb.attr('k-status', cl);
            });
            
            // действие при изменении
            $el.on('change', function() {
                var val = $el.val();
                if(val == $el.vChecked) {
                    if($el.onChecked) {
                        $el.onChecked();
                    }
                } else {
                    if(val == $el.vGrayed) {
                        if($el.onGrayed) {
                            $el.onGrayed();
                        }
                    } else {
                        if($el.onUnchecked) {
                            $el.onUnchecked();
                        }
                    }
                }
            });
            
            $el.trigger('refresh');

        });
        
};