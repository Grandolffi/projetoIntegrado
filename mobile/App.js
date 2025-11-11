import React from 'react';
import { StatusBar } from 'expo-status-bar';
import DrawerRoutes from './Frontend/src/routes/DrawerRoutes'; // Ajuste o caminho conforme sua estrutura

export default function App() {
  return (
    <>
      <DrawerRoutes /> 
      <StatusBar style="auto" />
    </>
  );
}
