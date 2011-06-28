	/**
	 * json serialize 
	 */

	/**
	 * serialize function itself
	 * @param mixed_value
	 */
	function serialize( mixed_value ) {
		serialize.__ref_found=false;
		var save_ref_cnt=serialize.__ref_cnt;
		// first run. if no references found - return result.
		var ret=serialize._run(mixed_value,function(mixed_value){
			if(typeof mixed_value.__cnt!='undefined'){ // seen hime somethere, 
		    	serialize.__ref_found=true;
		    	mixed_value.__cnt=-mixed_value.__cnt;
		    	mixed_value.__ref=true;
		    	return false;
			} else {
		    	mixed_value.__cnt=++serialize.__ref_cnt;
		    	serialize.__store[mixed_value.__cnt]=mixed_value;
		    }
			return true;
			
		});
		
		// second run. resolve founded referrences and return result.
		if(serialize.__ref_found)
			ret= serialize._run(mixed_value,function(mixed_value){
				if(mixed_value.__ref){
					if(mixed_value.__cnt<0){
						mixed_value.__cnt=-mixed_value.__cnt;
					} else {
						return '_REF('+mixed_value.__cnt+')';
					}
	    		} else {
	    			delete mixed_value.__cnt;
	    		}
				return true;
			}, function(mixed_value,val){
			    if(mixed_value.__cnt && mixed_value.__cnt>0){
			    	return '_REF('+mixed_value.__cnt+','+val+')';
			    }
			    return val;
			});

		for(var i=save_ref_cnt;i<serialize.__ref_cnt;i++){
			if(serialize.__store[i] && serialize.__store[i].__cnt) {
				delete serialize.__store[i].__cnt;
				delete serialize.__store[i].__ref;
			}
		}
		serialize.__ref_cnt=save_ref_cnt;
		return ret;
	}
	/**
     * index autoincremented with new reference
     */
	serialize.__ref_cnt=0;

	/**
	 * array of all links
	 */
//	serialize._references=[];
	/**
	 * mark oject with ID
	 */
	serialize.mark= function(obj,ind){
		if(ind)
			obj.__ref=ind;
		else 
			obj.__ref=++serialize.__ref_cnt;
	};	
	
	serialize.__store =[];
	
	serialize._run=function(mixed_value,before,after){
	    // json serialize
	    var val=before(mixed_value);
	    if(val===false) return '';
	    if(val!==true) return val;

    	switch (serialize._getType(mixed_value)) {
	        case "function": 
	            val = "null"; // later we'll try to solve this problem
	            break;
	        case "boolean":
	            val = (mixed_value ? "true" : "false");
	            break;
	        case "number":
	            val = mixed_value;
	            break;
	        case "string":
	            val = "'"+mixed_value
	            			.replace("'",'\\')
	            			.replace("\\",'\\\\')
	            			.replace("\n",'\\n')
	            			.replace("\r",'')+"'";
	            break;
	        case "array":
	        	val=[];
	        	for(i=0;i<mixed_value.length;i++)
	        	{
	        		val.push(serialize._run(mixed_value[i],before,after));
	        	}
	        	val='['+val.join(',')+']';
	            break;
	        case "object":
	        	val=[];
	        	for(a in mixed_value)
		        	if(a.substring(0,1)!='_')	
		        	{
		        		val.push(a+':'+serialize._run(mixed_value[a],before,after));
		        	}
	        	val='{'+val.join(',')+'}';
	            break;
	        case "undefined": 
	        	val = "void(0)";
	            break;
	        default: 
	            val = "null";
	            break;
	    }
    	if(after) {
    		var val=after(mixed_value,val);
    	    if(val===false) return '';
    	    return val;
    	};
	    
	    return val;
	};
	
	serialize._getType = function ( inp ) {
        var type = typeof inp, match;
        var key;
        if (type == 'object' && !inp) {
            return 'null';
        }
        if (type == "object") { 
            if (!inp.constructor) {
                return 'object';
            }
            var cons = inp.constructor.toString();
            match = cons.match(/(\w+)\(/);
            if (match) {
                cons = match[1].toLowerCase();
            }
            type = serialize._type[cons] || type;
        }
        return type;
    };

    serialize._type={boolean:"boolean",number: "number",string: "string",array: "array"};

	function unserialize(s){
		return eval(s);
	}