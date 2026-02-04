const { defineConfig } = require('@vue/cli-service')

module.exports = defineConfig({
  transpileDependencies: true,
  publicPath: process.env.NODE_ENV === 'production'
    ? '/cos20031/s104608220/dist/' // Set to the path where your app is served.
    : '/'
})