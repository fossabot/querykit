const path = require('path')

module.exports = {
  mode: 'production',
  entry: './assets/src/index.ts',
  watch: true,
  watchOptions: {
    ignored: ['node_modules/**']
  },
  module: {
    rules: [
      {
        test: /\.tsx?$/,
        use: 'ts-loader',
        exclude: /node_modules/
      },
      {
        test: /\.s[ac]ss$/i,
        use: ['style-loader', 'css-loader', 'sass-loader']
      }
    ]
  },
  resolve: {
    extensions: ['.tsx', '.ts', '.js']
  },

  output: {
    filename: 'rxp.js',
    path: path.resolve(__dirname, 'assets'),
    libraryTarget: 'var',
    library: 'rxp'
  }
}
