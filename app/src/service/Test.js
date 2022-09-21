export function one() {
    let h = document.createElement('h1');
    h.textContent = 'Hello';
    let one = document.querySelector('.wrapper');
    one.appendChild(h);
}

export class Test {
    one() {
        let h = document.createElement('h1');
        h.textContent = 'Hello';
        let one = document.querySelector('.wrapper');
        one.appendChild(h);
    }
}