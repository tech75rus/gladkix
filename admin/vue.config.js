module.exports = {
    pwa: {
        name: 'admin.gladkix.com',
        themeColor: '#222',
        workboxOptions: {
            skipWaiting: true
        }
    },
    devServer: {
        proxy: 'https://127.0.0.1:8000/',
    }
}
