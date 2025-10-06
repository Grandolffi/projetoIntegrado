import React from 'react';
import { createDrawerNavigator } from '@react-navigation/drawer';
import { NavigationContainer } from '@react-navigation/native';
import { Feather } from '@expo/vector-icons';
import { SafeAreaProvider } from 'react-native-safe-area-context';

// Importe APENAS as telas que já criamos (Home e NovoExame).
import Home from '../pages/Home';
import NovoExame from '../pages/NovoExame'; 
import CadastroPaciente from '../pages/CadastroPaciente';
import ListaPacientes from '../pages/ListagemPacientes';


const Drawer = createDrawerNavigator();

export default function DrawerRoutes() {
  return (
    <SafeAreaProvider>
      <NavigationContainer>
        <Drawer.Navigator
          initialRouteName="Home"
          screenOptions={{
            headerShown: false, 
            drawerActiveTintColor: '#0A212F', 
            drawerInactiveTintColor: '#586A76', 
            drawerLabelStyle: {
              marginLeft: -20, 
              fontWeight: 'bold',
              fontSize: 16,
            },
            drawerStyle: {
              paddingTop: 50, 
            }
          }}
        >
          {/* Rota Home: Tela Inicial com o Menu */}
          <Drawer.Screen 
            name="Home" 
            component={Home} 
            options={{
              drawerLabel: "Menu Principal",
              drawerIcon: ({ color, size }) => (
                <Feather name="home" color={color} size={size} />
              )
            }}
          />
          
          {/* Rota NovoExame: Tela de Exemplo de Formulário */}
          <Drawer.Screen 
            name="NovoExame" 
            component={NovoExame} 
            options={{
              drawerLabel: "Solicitar Novo Exame",
              drawerIcon: ({ color, size }) => (
                <Feather name="file-text" color={color} size={size} />
              )
            }}
          />
          <Drawer.Screen 
              name="CadastroPaciente" 
              component={CadastroPaciente} 
              options={{
                  drawerLabel: "Cadastrar Novo Paciente",
                  drawerIcon: ({ color, size }) => (
                      <Feather name="user-plus" color={color} size={size} /> // 💡 Sugestão de ícone mais específico
                  )
              }}
          />
          <Drawer.Screen 
                name="ListaPacientes" 
                component={ListaPacientes} 
                options={{
                    drawerLabel: "Listagem de Pacientes",
                    drawerIcon: ({ color, size }) => (
                        <Feather name="users" color={color} size={size} /> // 💡 Sugestão de ícone para listagem de pessoas
                    )
                }}
            />
          
          

        {/*
        * ROTAS FUTURAS: 
        * Adicione as seguintes rotas SOMENTE depois de criar o respectivo arquivo index.js:
        * * <Drawer.Screen name="CadastrarPaciente" component={CadastrarPaciente} ... />
        * <Drawer.Screen name="ListaExames" component={ListaExames} ... />
        */}
          
        </Drawer.Navigator>
      </NavigationContainer>
    </SafeAreaProvider>
  );
}
