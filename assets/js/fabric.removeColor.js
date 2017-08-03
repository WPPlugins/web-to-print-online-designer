(function (global){

	'use strict';

	var fabric=global.fabric||(global.fabric={}),
			extend=fabric.util.object.extend;

	/**
	 * Remove Color filter class
	 * @class fabric.Image.filters.RemoveColor
	 * @memberOf fabric.Image.filters
	 * @extends fabric.Image.filters.BaseFilter
	 * @example
	 * var filter = new fabric.Image.filters.RemoveColor({
	 *   color: [235,158,84]
	 * });
	 * object.filters.push(filter);
	 * object.applyFilters(canvas.renderAll.bind(canvas));
	 */
	fabric.Image.filters.RemoveColor=fabric.util.createClass(fabric.Image.filters.BaseFilter, /** @lends fabric.Image.filters.RemoveColor.prototype */ {
		/**
		 * Filter type
		 * @param {String} type
		 * @default
		 */
		type: 'RemoveColor',
		/**
		 * Constructor
		 * @memberOf fabric.Image.filters.RemoveColor.prototype
		 * @param {Object} [options] Options object
		 * @param {Number} [options.color=[255,255,255]] Color to Remove
		 */
		initialize: function (options){
			options=options||{};
			this.color=options.color||[255, 255, 255];
		},
		/**
		 * Applies filter to canvas element
		 * @param {Object} canvasEl Canvas element to apply filter to
		 */
		applyTo: function (canvasEl){
			var context=canvasEl.getContext('2d'),
					imageData=context.getImageData(0, 0, canvasEl.width, canvasEl.height),
					data=imageData.data,
					color=this.color,
					r, g, b;

			for(var i=0, len=data.length; i<len; i+=4){
				r=data[i];
				g=data[i+1];
				b=data[i+2];

				if(r==color[0]&&g==color[1]&&b==color[2]){
					data[i+3]=1;
				}
			}

			context.putImageData(imageData, 0, 0);
		},
		/**
		 * Returns object representation of an instance
		 * @return {Object} Object representation of an instance
		 */
		toObject: function (){
			return extend(this.callSuper('toObject'), {
				color: this.color
			});
		}
	});

	/**
	 * Returns filter instance from an object representation
	 * @static
	 * @param {Object} object Object to create an instance from
	 * @return {fabric.Image.filters.RemoveColor} Instance of fabric.Image.filters.RemoveColor
	 */
	fabric.Image.filters.RemoveColor.fromObject=function (object){
		return new fabric.Image.filters.RemoveColor(object);
	};

})(typeof exports!=='undefined'?exports:this);
