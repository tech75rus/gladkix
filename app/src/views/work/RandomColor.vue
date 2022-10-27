<template>
  <div class="random-color">
    <button data-type="update" class="update">Нажать или пробел</button>
    <div class="column">
      <div>
        <h2 data-type="text">Text</h2>
        <span>Скопировано!</span>
      </div>
      <button>
        <i data-type="lock" class="fa-solid fa-lock-open"></i>
      </button>
    </div>
    <div class="column">
      <div>
        <h2 data-type="text">Text</h2>
        <span>Скопировано!</span>
      </div>
      <button>
        <i data-type="lock" class="fa-solid fa-lock-open"></i>
      </button>
    </div>
    <div class="column">
      <div>
        <h2 data-type="text">Text</h2>
        <span>Скопировано!</span>
      </div>
      <button>
        <i data-type="lock" class="fa-solid fa-lock-open"></i>
      </button>
    </div>
    <div class="column">
      <div>
        <h2 data-type="text">Text</h2>
        <span>Скопировано!</span>
      </div>
      <button>
        <i data-type="lock" class="fa-solid fa-lock-open"></i>
      </button>
    </div>
    <div class="column">
      <div>
        <h2 data-type="text">Text</h2>
        <span>Скопировано!</span>
      </div>
      <button>
        <i data-type="lock" class="fa-solid fa-lock-open"></i>
      </button>
    </div>
  </div>
</template>
<script>
import chroma from "chroma-js";

export default {
  name: 'RandomColor',
  methods: {
    hideMenu() {
      document.querySelector('.menu-desktop').style.display = 'none';
    },
    linkFontAwesome() {
      const link = document.createElement('link');
      link.rel = 'stylesheet';
      link.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css';
      link.integrity = 'sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==';
      link.crossOrigin = 'anonymous';
      link.referrerPolicy = 'no-referrer';
      document.head.appendChild(link);
    },
    setRandomColor(columns, isInit) {
      const hashColor = isInit ? this.getHashColor() : [];
      columns.forEach((column, index) => {
        const isLocked = column.querySelector('i').classList.contains('fa-lock')
        const text = column.querySelector('h2');
        const button = column.querySelector('i');

        if (isLocked) {
          hashColor.push(text.textContent);
          return;
        }

        const color = isInit
            ? hashColor[index] ? hashColor[index] : chroma.random()
            : chroma.random();

        hashColor.push(color);
        text.textContent = color;
        column.style.backgroundColor = color;

        this.textColor(text, color);
        this.textColor(button, color);
      })
      this.updateHashColor(hashColor)
    },
    textColor(element, color) {
      element.style.color = chroma(color).luminance() > 0.5 ? '#2c3e50' : 'white';
    },
    getHashColor() {
      const hash = document.location.hash;
      if (hash.length > 1) {
        return hash.substring(1).split('-').map(item => {
          return '#' + item;
        })
      }
      return [];
    },
    updateHashColor(colors = []) {
      document.location.hash = colors.map(color => {
        return color.toString().substring(1);
      }).join('-');
    },
    showCopyText(element) {
      element.nextSibling.style.visibility = 'visible';
      element.nextSibling.style.opacity = '1';
      setTimeout(function () {
        element.nextSibling.style.visibility = 'hidden';
        element.nextSibling.style.opacity = '0';
      }, 1000);
    },
  },
  created() {
    this.hideMenu();
    this.linkFontAwesome();
  },
  mounted() {
    const columns = document.querySelectorAll('.column');
    document.addEventListener('keydown', event => {
      event.preventDefault();
      if (event.code.toLowerCase() === 'space') {
        this.setRandomColor(columns);
      }
    })
    document.addEventListener('click', event => {
      const elem = event.target;
      if (elem.dataset.type === 'lock') {
        elem.classList.toggle('fa-lock-open');
        elem.classList.toggle('fa-lock');
      }

      if (elem.dataset.type === 'text') {
        navigator.clipboard.writeText(elem.textContent);
        this.showCopyText(elem);
      }

      if (elem.dataset.type === 'update') {
        this.setRandomColor(columns);
      }
    })
    this.setRandomColor(columns, true);
  }
}
</script>

<style lang="scss" scoped>
.wrapper {
  margin: 0;
  max-width: none;
  min-width: 320px;
  padding: 0;
}
.random-color {
  display: flex;
  justify-content: space-around;
  height: 100vh;
}
.column {
  display: flex;
  flex-direction: column;
  justify-content: space-around;
  align-items: center;
  width: 100%;
//  border: 1px solid #333;

  h2 {
    padding: 5px 8px;
    border-radius: 10px;
    transition: background-color .3s;
  }
  h2:hover {
    cursor: pointer;
    background-color: rgba(0, 0, 0, .3);
  }
  span {
    position: relative;
    padding: 7px;
    background-color: rgba(255, 255, 255, .5);
    border-radius: 10px;
    font-weight: bold;
//    margin-top: 5px;
    top: 10px;
    transition: .3s;
    opacity: 0;
    visibility: hidden;
  }
  button {
    border: none;
    background-color: transparent;
    outline: none;
  }
  i {
    font-size: 1.5rem;
    border-radius: 10px;
    padding: 10px;
    transition: background-color .3s;
    outline: none;
  }
  i:hover {
    cursor: pointer;
    background-color: rgba(0, 0, 0, .3);
  }

}
.update {
  position: fixed;
  top: 4vh;
  border: 1px solid #2c3e50;
  background-color: rgba(255, 255, 255, .5);
  padding: 5px 8px;
  border-radius: 7px;
  font-size: 1.2rem;
  width: 125px;
  cursor: pointer;
  outline: none;
}
@media (max-width: 700px) {
  .update {
    font-size: 1rem;
    width: 106px;
  }
  .column {
    h2 {
      font-size: 1rem;
    }
    span {
      font-size: .8rem;
    }
    i {
      font-size: 1rem;
    }
  }
}
@media (max-width: 500px) {
  .update {
    font-size: .8rem;
    width: 88px;
  }
  .column {
    h2 {
      font-size: .7rem;
    }
    span {
      font-size: .47rem;
    }
    i {
      font-size: 1rem;
    }
  }
}
</style>