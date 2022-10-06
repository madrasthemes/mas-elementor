/**
	 * Typed Headline.
	 */
 var animatedHeadline = function() {
    // forEach function
    var forEach = function forEach( array, callback, scope ) {
        for ( var i = 0; i < array.length; i++ ) {
            callback.call(scope, i, array[i]); // passes back stuff we need
        }
    };

    var typeds = document.querySelectorAll( '.typed-text' );
    forEach( typeds, function ( index, value ) {
        var defaults = {
            typeSpeed: 40,
            backSpeed: 40,
            backDelay: 1000,
            loop: true
        };
        var userOptions;
        if ( value.dataset.typedOptions != undefined ) userOptions = JSON.parse( value.dataset.typedOptions );
        var options = Object.assign({}, defaults, userOptions);
        var animatedHeadline = new Typed( value, options );
    } );
}();