import { Configuration } from "webpack";
import * as MiniCssExtractPlugin from "mini-css-extract-plugin";

const config: Configuration = {
  entry: {
    'admin/assets/admin': './src/admin.ts',
    'public/assets/public': './src/public.ts',
    'public/assets/location-map': './src/location-map.ts'
  },
  output: {
    filename: '[name].js',
    path: __dirname,
    library: 'wplocator'
  },
  mode: process.env.NODE_ENV === 'dev' ? 'development' : 'production',
  module: {
    rules: [
      { test: /\.ts$/, loader: 'ts-loader' },
      {
        test: /\.css$/,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
          'postcss-loader',
        ]
      }
    ],
  },
  resolve: {
    extensions: ['.js', '.ts'],
  },
  plugins: [
      new MiniCssExtractPlugin({
        filename: '[name].css'
      })
  ]
};

export default config;
