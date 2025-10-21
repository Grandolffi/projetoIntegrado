// Arquivo: mobile/babel.config.js

module.exports = function(api) {
  api.cache(true);
  return {
    presets: ['babel-preset-expo'],
    plugins: [
      // Este plugin deve ser o ÚLTIMO da lista para o Reanimated funcionar
      'react-native-reanimated/plugin',
    ],
  };
};