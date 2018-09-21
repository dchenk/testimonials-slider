(function() {
	jQuery(".testimonials-slider").each(function(_, wrapper) {
		const slide = function(lr, list) {
			const width = this.getBoundingClientRect().width;
			const listLeft = list.getBoundingClientRect().left;
			var listWidth = 0;
			jQuery(list).children().each(function(_, elem) {
				listWidth += jQuery(elem).outerWidth(true);
			});
			// const marginLeft = list.style.
			if (lr === "left") {
			} else {
				console.log("right")
			}
		};
		const list = wrapper.querySelector(".testimonials-slider-list");
		wrapper.querySelector(".testimonials-slider-left").addEventListener("click", slide.bind(wrapper, "left", list));
		wrapper.querySelector(".testimonials-slider-right").addEventListener("click", slide.bind(wrapper, "right", list));
	})
})();