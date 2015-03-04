
(function(){

    var Installer = function(baseUrl, el, title) {
        this.title = title;
        this.baseURL = baseUrl;
        this.actions = [];
        this.currentIndex = 0;
        if (el instanceof Element) {
            this.container = el;
        } else {
            var that = this;
            that.container = $(document.body);
        }
    };

    Installer.prototype = {
        complete: function () {
            if ("function" == typeof this.onComplete) {
                this.onComplete();
            }
        },
        runNext: function() {
            if (this.currentIndex < this.actions.length) {
                var action = this.actions[this.currentIndex];
                action.xhr.send();
                this.currentIndex++;
            } else if (this.currentIndex > 0) {
                this.complete();
            }
        },
        placeholderCreate: function (action) {
            var phEl = this.container.getElement('.lgl-install-placeholder');
            if (!phEl) {
                phEl = $(document.createElement('div'));
                phEl.addClass('lgl-install-placeholder');
                if (this.title) {
                    phEl.innerHTML += '<label>' + this.title + '</label>'
                }
                phEl.innerHTML += '<ol></ol>';
                this.container.grab(phEl);
            }
            var el = new Element('li', {
                'data-id': action.name,
                'data-status': 'default',
                html: action.text + ' <span></span>'
            });
            if (null !== action.displayOnCreate && action.displayOnCreate) {
                el.addClass('visible');
            }
            phEl.getFirst('ol').grab(el);
        },

        findPlaceholder: function (key) {
            return this.container.getElement('.lgl-install-placeholder [data-id=' + key + ']');
        },
        placeholderLoading: function (key) {
            var ph = this.findPlaceholder(key);
            ph.setProperty('data-status', 'progress');
            ph.getFirst('span').innerText = 'In Progress...';
        },
        placeholderComplete: function (key, response) {
            var ph = this.findPlaceholder(key);
            ph.setProperty('data-status', 'complete');
            ph.getFirst('span').innerText = 'Complete';
        },
        placeholderFail: function (key, response) {
            var ph = this.findPlaceholder(key);
            ph.setProperty('data-status', 'failed');
            ph.getFirst('span').innerHTML = 'Failed';
            if (null != response && null != response.error) {
                ph.setProperty('title', response.error);
                new Tips(ph, {className: 'lgl-installer-tip'});
            }
        },
        addAction: function(action) {
            var that = this;
            action.xhr = new Request.JSON({
                url: this.baseURL + action.url,
                method: 'GET',
                noCache: true,
                async: true,
                onRequest: function(){
                    that.placeholderLoading(action.name);
                },
                onSuccess: function(response){
                    if (null != response.status && 'ok' == response.status) {
                        that.placeholderComplete(action.name, response);
                        if ("function" == typeof action.onSuccess) {
                            action.onSuccess(response);
                        }
                        that.runNext();
                    } else {
                        that.placeholderFail(action.name, response);
                        if ("function" == typeof action.onFailure) {
                            action.onFailure(response);
                        }
                        if (null !== action.breakOnFailure && false === action.breakOnFailure) {
                            that.runNext();
                        }
                    }
                },
                onError: function(response){
                    that.placeholderFail(action.name, response);
                    if ("function" == typeof action.onFailure) {
                        action.onFailure(response);
                    }
                    if (null !== action.breakOnFailure && false === action.breakOnFailure) {
                        that.runNext();
                    }
                },
                onFailure: function(){
                    if ("function" == typeof action.onFailure) {
                        action.onFailure(response);
                    }
                    that.placeholderFail(action.name);
                }
            });
            this.actions.push(action);
            this.placeholderCreate(action);
        }
    };
    window.LGLInstaller = Installer;
})();
