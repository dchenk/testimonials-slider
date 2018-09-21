(function() {
	function setLeft(elem, leftPixels) {
		elem.style.marginLeft = leftPixels + "px";
	}
	jQuery(".testimonials-slider").each(function(_, wrapper) {
		const slide = function(lr, list) {
			const wrapperRect = this.getBoundingClientRect();
			const width = wrapperRect.width;
			const left = wrapperRect.left;
			const listLeft = list.getBoundingClientRect().left;
			var listWidth = 0;
			jQuery(list).children().each(function(_, elem) {
				listWidth += jQuery(elem).outerWidth(true);
			});
			const currentShift = listLeft - left;
			if (lr === "right") {
				if ((-currentShift) < (listWidth - width)) {
					let newShift = currentShift - 320;
					if ((newShift < 0) && (listWidth + newShift < width)) {
						newShift = width - listWidth;
					}
					setLeft(list, newShift);
				}
			} else {
				if (currentShift < 0) {
					let newShift = currentShift + 320;
					if (newShift > 0) {
						newShift = 0;
					}
					setLeft(list, newShift);
				}
			}
		};
		const list = wrapper.querySelector(".testimonials-slider-list");
		wrapper.querySelector(".testimonials-slider-left").addEventListener("click", slide.bind(wrapper, "left", list));
		wrapper.querySelector(".testimonials-slider-right").addEventListener("click", slide.bind(wrapper, "right", list));
	})
})();