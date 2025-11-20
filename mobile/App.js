import React from 'react';
import { StatusBar } from 'expo-status-bar';
import DrawerRoutes from './Frontend/src/routes/DrawerRoutes';
import Toast from 'react-native-toast-message';

export default function App() {
  return (
    <>
      
      <DrawerRoutes /> 
      <StatusBar style="auto" />
      <Toast />
    </>
  );
}
