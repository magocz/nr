function formatDouble(val) {
    // Need to extend String prototype for convinience
    String.prototype.reverse = function () {
        return this.split('').reverse().join('');
    };

    val = parseFloat(val).toFixed(2);
    return val.toString().reverse().replace(/((?:\d{2})\d)/g, '$1 ').reverse();
}

