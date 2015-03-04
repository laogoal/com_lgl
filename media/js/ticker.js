(function(){
    var Ticker = function(url, timestamp) {
        this.siteUrl = url;
        this.luts = timestamp;
        this.busy = false;
        this.updateListeners = [];

        var that = this;
        this.XHR = new Request.JSON({
            url: prepareURL(this.siteUrl),
            method: 'GET',
            noCache: true,
            async: true,
            onRequest: function(){
                that.busy = true;
            },
            onComplete: function(){
                that.busy = false;
            },
            onError: function(text, error) {
                that.busy = false;
                if (console) {
                    console.log(error, text);
                }
            },
            onSuccess: function(response){
                try {
                    processResponse(that, response);
                } catch (x) {
                    if (console) {
                        console.log(x);
                    }
                }
            }
        });
    };

    Ticker.prototype = {
        ping: function() {
            if (this.busy) {
                return false;
            }
            this.XHR.send({
                data: 'lu=' + this.luts
            });
            return true;
        },

        addUpdateListener: function(callback) {
            if ('function' != typeof callback) {
                throw new Error('Trying to register invalid update listener');
            }
            this.updateListeners.push(callback);
            return true;
        }
    };

    /**
     *
     * @param {window.LGLTicker} ticker
     * @param response
     */
    function processResponse(ticker, response) {
        if (null == response.status) {
            throw new Error('Response is invalid');
        }
        if ('OK' != response.status) {
            if (null != response.message) {
                throw new Error("Can't perform XHR request. " + response.message);
            } else {
                throw new Error("Can't perform XHR request");
            }
        }
        ticker.luts = response.luts;
        if (null != response.items && response.items.length) {
            Array.each(ticker.updateListeners, function(listener){
                listener.call(null, response.items);
            });
        }
    }

    function prepareURL(url) {
        return url + 'index.php?option=com_lgl&task=ping';
    }

    Ticker.createTicker = function(url, timestamp, callback){
        var ticker = new Ticker(url, timestamp);
        ticker.addUpdateListener(callback);
        window.setInterval(function(){ticker.ping()}, 10000);

    };
    window.LGLTicker = Ticker;
})();
