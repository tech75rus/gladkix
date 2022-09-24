export class Article {

    constructor(data, selector = '.wrapper') {
        if (!data) { return 'Нет данных'; }
        this.data = JSON.parse(data);
        this.showArticle(this.data.blocks, selector);
    }

    showArticle(data, selector) {
        for (let block in data) {
            let dataBlock = data[block].data;
            let dataType = data[block].type;
            console.log(dataType);
            console.log(dataBlock);
            this.methods[dataType](dataBlock, selector);
        }
    }

    methods = {
        'header': (data, selector) => {
            let wrapper = document.querySelector(selector);
            let h2 = document.createElement('h2');
            h2.textContent = data.text;
            wrapper.appendChild(h2);
        },
        'paragraph': (data, selector) => {
            let wrapper = document.querySelector(selector);
            let p = document.createElement('p');
            p.innerHTML = data.text;
            wrapper.appendChild(p);
        },
        'image': (data, selector) => {
            let wrapper = document.querySelector(selector);
            let img = document.createElement('img');
            img.src = data.file.url;
            img.alt = data.caption;
            wrapper.appendChild(img);
        },
        'raw': (data, selector) => {
            let wrapper = document.querySelector(selector);
            let pre = document.createElement('pre');
            let code = document.createElement('code');
            wrapper.appendChild(pre);
            pre.appendChild(code);
            code.classList.add('plaintext');
            code.innerText = data.html;
        },
    }
}