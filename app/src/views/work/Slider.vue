<template>
  <div>
    <div class="slider">
      <div class="slider-list">
        <div class="slider-track">
          <div class="slide"><img src="../../assets/images/slider/1.jpg" alt="image 1"></div>
          <div class="slide"><img src="../../assets/images/slider/2.jpg" alt="image 2"></div>
          <div class="slide"><img src="../../assets/images/slider/3.jpg" alt="image 3"></div>
          <div class="slide"><img src="../../assets/images/slider/4.jpg" alt="image 4"></div>
          <div class="slide"><img src="../../assets/images/slider/5.jpg" alt="image 5"></div>
          <div class="slide"><img src="../../assets/images/slider/6.jpg" alt="image 6"></div>
        </div>
      </div>
      <div class="slider-arrows">
        <button class="prev disabled">&larr;</button>
        <button class="next">&rarr;</button>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'Slider',
  data() {
    return {
      data: ''
    }
  },
  mounted() {
    let slider = document.querySelector('.slider'),
        sliderList = slider.querySelector('.slider-list'),
        sliderTrack = slider.querySelector('.slider-track'),
        sliders = slider.querySelectorAll('.slide'),
        arrows = slider.querySelector('.slider-arrows'),
        prev = arrows.children[0],
        next = arrows.children[1],
        posInit = 0,
        posX1 = 0,
        posX2 = 0,
        posFinal = 0,
        slideIndex = 0,
        slideWidth = sliders[0].offsetWidth,
        posThreshold = slideWidth * 0.3,
        reg = /([-0-9.]+(?=px))/,
        getEnv = function() {
          return (event.type.search('touch') !== -1) ? event.touches[0] : event;
        },
        slide = function() {
          sliderTrack.style.transform = `translate3d(-${slideIndex * slideWidth}px, 0px, 0px)`;

          prev.classList.toggle('disabled', slideIndex === 0);
          next.classList.toggle('disabled', slideIndex === sliders.length - 1);
        },
        swipeStart = function () {
          let evt = getEnv();
          posX1 = posInit = evt.clientX;
          sliderTrack.style.transition = '';

          document.addEventListener('touchmove', swipeAction);
          document.addEventListener('mousemove', swipeAction);
          document.addEventListener('touchend', swipeEnd);
          document.addEventListener('mouseup', swipeEnd);
        },
        swipeAction = function() {
          let evt = getEnv();
          let transform = +sliderTrack.style.transform.match(reg)[0];

          posX2 = posX1 - evt.clientX;
          posX1 = evt.clientX;
          if (slideIndex === 0 && posX2 < 0) {
            return;
          } else if (slideIndex === sliders.length - 1 && posX2 > 0) {
            return;
          }

          sliderTrack.style.transform = `translate3d(${transform - posX2}px, 0px, 0px)`;
        },
        swipeEnd = function() {
          sliderTrack.style.transition = 'transform .5s';
          posFinal = posInit - posX1;

          document.removeEventListener('touchmove', swipeAction);
          document.removeEventListener('mousemove', swipeAction);
          document.removeEventListener('touchend', swipeEnd);
          document.removeEventListener('mouseup', swipeEnd);

          if (Math.abs(posFinal) > posThreshold) {
            if (posInit < posX1 && slideIndex !== 0) {
              slideIndex--;
            } else if (posInit > posX1 && slideIndex !== sliders.length - 1) {
              slideIndex++;
            } else {
              return;
            }
          }

          if (posInit !== posX1) {
            slide();
          }
        }

    sliderList.addEventListener('touchstart', swipeStart);
    sliderList.addEventListener('mousedown', swipeStart);
    sliderTrack.style.transform = 'translate3d(0px, 0px, 0px)';
    sliderTrack.style.transition = 'transform .5s';
    slide();

    arrows.addEventListener('click', function() {
      let target = event.target;
      if (target.classList.contains('prev')) {
        slideIndex--;
      } else if (target.classList.contains('next')) {
        slideIndex++;
      }
      slide();
    });
  },
}
</script>

<style lang="scss" scoped>
.slider {
  position: relative;
  width: 200px;
  margin: 30px auto 0;

  img {
    pointer-events: none;
  }
}
.slider-list {
  width: 200px;
  height: 200px;
  overflow: hidden;
}
.slider-track {
  display: flex;
}
.slide {
  width: 200px;
  height: 200px;
  flex-shrink: 0;

  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 40px;

  user-select: none;

  img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
}
.slider-arrows {
  text-align: center;
  margin-top: 20px;

  button {
    border: none;
    background-color: transparent;
    font-size: 30px;
    cursor: pointer;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
  }
}

.next.disabled,
.prev.disabled {
  opacity: .25;
  pointer-events: none;
}

</style>