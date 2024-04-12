const path = require("path");
const webpack = require("webpack");



module.exports = (env) => {
    console.log('env.production:', env.production);
    const isProduction = env.production === 'true';
    const basePath = isProduction ? "public/module" : "public/module"
    const baseJsPath = isProduction ? "public/js" : "public/js"
    const baseIndexPath = isProduction ? "public" : "public"

    return{
        mode: isProduction ? 'production' : 'development',
        
        entry: {
            domainDetail: path.resolve(__dirname, `${baseJsPath}/domainDetail.js`),
            domainManagement: path.resolve(__dirname, `${baseJsPath}/domainManagement.js`),
            domainShow: path.resolve(__dirname, `${baseJsPath}/domainShow.js`),
            infiniteScroll: path.resolve(__dirname, `${baseJsPath}/infiniteScroll.js`),
            sortDomainDataByCategory: path.resolve(__dirname, `${baseJsPath}/sortDomainDataByCategory.js`),
            tagManagement: path.resolve(__dirname, `${baseJsPath}/tagManagement.js`),
            tagEdit: path.resolve(__dirname, `${baseJsPath}/tagEdit.js`),
        },
        resolve: {
            alias: {
            // エイリアスの設定
            '@modules': path.resolve(__dirname, basePath),
            '@index': path.resolve(__dirname, baseIndexPath),

            // 他のエイリアスも同様に設定可能
            }
        },
        output: {
            path: path.resolve(__dirname, 'dist'),
            filename: '[name].js',
        },
        plugins: [
            // DefinePluginを使って環境変数を設定
            new webpack.DefinePlugin({
                'process.env': {
                    // ここに環境に応じた変数を定義
                    API_URL: JSON.stringify(isProduction? '/src/fetch' : '/tag_admin/src/fetch'),
                    SYSTEM_URL: JSON.stringify(isProduction ? '/' : '/tag_admin/'),
                }
            })
        ],
    }
   
}