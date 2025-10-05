import React from 'react';
import { StatusBar } from 'expo-status-bar';
import DrawerRoutes from './Frontend/src/routes/DrawerRoutes'; // Ajuste o caminho conforme sua estrutura

export default function App() {
  return (
    <>
      {/* Renderiza o componente principal de rotas (DrawerRoutes).
        Toda a interface do usuário será renderizada a partir daqui.
      */}
      <DrawerRoutes /> 
      <StatusBar style="auto" />
    </>
  );
}
