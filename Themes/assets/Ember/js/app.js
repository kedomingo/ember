/**
 * Shorthand function for checking whether a property exists
 * 
 * @description http://stackoverflow.com/a/6571640
 * @example
 * 
        var data = { foo: { bar: 42 } };
        isDefined(data, "foo"); // true
        isDefined(data, "foo.bar"); // true
        isDefined(data, "notfoo"); // false
        isDefined(data, "foo.baz"); // false

 */
function isDefined(target, path) {
    
    if (typeof target != 'object') {
        try {
            target = $.parseJSON(target);
        } catch(e) {
            return false;
        }
    }
    
    var parts = path.split('.');

    while(parts.length) {
        var branch = parts.shift();
        if (!(branch in target)) {
            return false;
        }

        target = target[branch];
    }

    return true;
}

/**
 * Determines of the response status is 0
 */
function response_successful( response_obj ) {
    
    if (typeof response_obj != 'object') {
        try {
            response_obj = $.parseJSON(response_obj);
        } catch(e) {
            return false;
        }
    }
    
    return (isDefined(response_obj, 'response.status.code') && response_obj.response.status.code === 0);
}

/**
 * Decodes the data from the response if possible
 */
function response_data( response_obj ) {
    
    if (typeof response_obj != 'object') {
        try {
            data = $.parseJSON(data);
        } catch (e){
            return null;
        }
    }
    
    if (!response_successful(response_obj) || !isDefined(response_obj, 'response.data')) {
        return null;
    }
    return response_obj.response.data;
}
