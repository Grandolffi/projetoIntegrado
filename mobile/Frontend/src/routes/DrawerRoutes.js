import React from 'react';
import { createDrawerNavigator } from '@react-navigation/drawer';
import { NavigationContainer } from '@react-navigation/native';
import { Feather } from '@expo/vector-icons';
import { SafeAreaProvider } from 'react-native-safe-area-context';

// Importações 
import Home from '../pages/Home';
import NovoExame from '../pages/NovoExame';
import CadastroPaciente from '../pages/CadastroPaciente';
import ListaPacientes from '../pages/ListagemPacientes';
import ResultadoExame from '../pages/ResultadoExame/resultadoExames';
import ListaSolicitacoes from '../pages/ListaSolicitacoes/solicitacoesPendentes';
import ListaLaudos from '../pages/ListaLaudos/listaLaudo';
import LoginUser from '../pages/LoginUser/loginUser'

const Drawer = createDrawerNavigator();

export default function DrawerRoutes() {
  return (
    <SafeAreaProvider>
      <NavigationContainer>
        <Drawer.Navigator
          initialRouteName="Login"
          screenOptions={{
            headerShown: false,
            drawerActiveTintColor: '#0A212F',
            drawerInactiveTintColor: '#586A76',
            drawerLabelStyle: {
              fontWeight: 'bold',
              fontSize: 16,
            },
            drawerItemStyle: {
              borderRadius: 8,
            },

            drawerStyle: {
              paddingTop: 50,
            }
          }}
        >
          {/* 1. Home */}
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

          {/* 1. Home */}
          <Drawer.Screen
            name="Login"
            component={LoginUser}
            options={{
              drawerItemStyle: { display: 'none' }
            }}
          />

          {/* 2. Novo Exame */}
          <Drawer.Screen
            name="SolicitarNovoExame"
            component={NovoExame}
            options={{
              drawerLabel: "Solicitar Novo Exame",
              drawerIcon: ({ color, size }) => (
                <Feather name="plus-circle" color={color} size={size} />
              )
            }}
          />

          {/* 3. Cadastro Paciente */}
          <Drawer.Screen
            name="CadastrarPaciente"
            component={CadastroPaciente}
            options={{
              drawerLabel: "Cadastrar Novo Paciente",
              drawerIcon: ({ color, size }) => (
                <Feather name="user-plus" color={color} size={size} />
              )
            }}
          />

          {/* 4. Listagem de Pacientes */}
          <Drawer.Screen
            name="ListaPacientes"
            component={ListaPacientes}
            options={{
              drawerLabel: "Listagem de Pacientes",
              drawerIcon: ({ color, size }) => (
                <Feather name="users" color={color} size={size} />
              )
            }}
          />

          {/* 5. Resultado de Exame */}
          <Drawer.Screen
            name='DetalheResultadoExame'
            component={ResultadoExame}
            options={{
              drawerLabel: "Resultado de Exame",
              drawerIcon: ({ color, size }) => (
                <Feather name='clipboard' color={color} size={size} />
              )
            }}
          />

          {/* 7. Lista de Laudos */}
          <Drawer.Screen
            name='ListaLaudos'
            component={ListaLaudos}
            options={{
              drawerLabel: "Lista de Laudos",
              drawerIcon: ({ color, size }) => (
                <Feather name='list' color={color} size={size} />
              )
            }}
          />

        </Drawer.Navigator>
      </NavigationContainer>
    </SafeAreaProvider>
  );
}