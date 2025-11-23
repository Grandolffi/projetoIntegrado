import React, { useState, useEffect } from 'react';
import { createDrawerNavigator } from '@react-navigation/drawer';
import { NavigationContainer } from '@react-navigation/native';
import { Feather } from '@expo/vector-icons';
import { SafeAreaProvider } from 'react-native-safe-area-context';

// Importações
import Home from '../pages/Home';
import NovoExame from '../pages/NovoExame';
import CadastroPaciente from '../pages/CadastroPaciente';
import ListaPacientes from '../pages/ListagemPacientes';
import CadastrarResultadoExame from '../pages/ResultadoExame/resultadoExames';
import ListaLaudos from '../pages/ListaLaudos/listaLaudo';
import LoginUser from '../pages/LoginUser/loginUser'
import { LogoutScreen } from '../pages/LogoutScreen/logout';
import ListaResultadosExames from '../pages/ListagemExamesConcluidos';
import AsyncStorage from '@react-native-async-storage/async-storage'; // Importe o AsyncStorage
import { ActivityIndicator, View, StyleSheet } from 'react-native'; // Para o loading

const Drawer = createDrawerNavigator();

export default function DrawerRoutes() {
  const [isLoading, setIsLoading] = useState(true);
  const [userToken, setUserToken] = useState(null);

  useEffect(() => {
        // Função para carregar o token salvo
        const loadToken = async () => {
            try {
                const token = await AsyncStorage.getItem('@user_token'); //chamando o token que foi salvo na pagina do login
                setUserToken(token);
            } catch (error) {
                console.error("Erro ao carregar token:", error);
            } finally {
                setIsLoading(false);
            }
        };

        loadToken();
    }, []);

    //tela de carregamento enquanto valida token 
    if (isLoading) { 
        return (
            <View style={styles.loadingContainer}>
                <ActivityIndicator size="large" color="#0A212F" />
            </View>
        );
    }

    //definindo a rota se tiver token home, se não login
    const initialRouteName = userToken ? "Home" : "Login";

  return (
    <SafeAreaProvider>
      <NavigationContainer>
        <Drawer.Navigator
          initialRouteName={initialRouteName}
          screenOptions={{
            headerShown: false,
            drawerActiveTintColor: "#0A212F",
            drawerInactiveTintColor: "#586A76",
            drawerLabelStyle: {
              fontWeight: "bold",
              fontSize: 16,
            },
            drawerItemStyle: {
              borderRadius: 8,
            },

            drawerStyle: {
              paddingTop: 50,
            },
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
              ),
            }}
          />

          {/* 1. Home */}
          <Drawer.Screen
            name="Login"
            component={LoginUser}
            options={{
              drawerItemStyle: { display: "none" },
            }}
          />

          {/* 1. Cadastro Paciente */}
          <Drawer.Screen
            name="CadastrarPaciente"
            component={CadastroPaciente}
            options={{
              drawerLabel: "Cadastrar Novo Paciente",
              drawerIcon: ({ color, size }) => (
                <Feather name="user-plus" color={color} size={size} />
              ),
              unmountOnBlur: true,
            }}
          />

          {/* 2. Listagem de Pacientes */}
          <Drawer.Screen
            name="ListaPacientes"
            component={ListaPacientes}
            options={{
              drawerLabel: "Listagem de Pacientes",
              drawerIcon: ({ color, size }) => (
                <Feather name="users" color={color} size={size} />
              ),
            }}
          />

          {/* 3. Novo Exame*/}
          <Drawer.Screen
            name="SolicitarNovoExame"
            component={NovoExame}
            options={{
              drawerLabel: "Solicitar Novo Exame",
              drawerIcon: ({ color, size }) => (
                <Feather name="plus-circle" color={color} size={size} />
              ),
            }}
          />

          {/* 4. Resultado de Exame */}
          <Drawer.Screen
            name="DetalheResultadoExame"
            component={CadastrarResultadoExame}
            options={{
              drawerLabel: "Cadastrar Resultado de Exame",
              drawerIcon: ({ color, size }) => (
                <Feather name="clipboard" color={color} size={size} />
              ),
            }}
          />

          {/* 5. Lista Exame Concluido*/}
          <Drawer.Screen
            name="ListaResultados" 
            component={ListaResultadosExames} 
            options={{
              drawerLabel: "Exames Concluídos",
              drawerIcon: ({ color, size }) => (
                <Feather name="check-square" color={color} size={size} />
              ),
            }}
          />

          {/* 5. Lista de Laudos */}
          <Drawer.Screen
            name="ListaLaudos"
            component={ListaLaudos}
            options={{
              drawerLabel: "Lista de Laudos",
              drawerIcon: ({ color, size }) => (
                <Feather name="list" color={color} size={size} />
              ),
            }}
          />

          <Drawer.Screen
                name="Logout"
                component={LogoutScreen} 
                options={{
                    drawerLabel: "Sair do Sistema",
                    drawerIcon: ({ color, size }) => (
                        <Feather name="log-out" color={color} size={size} />
                    ),
                      // Garante que o componente seja desmontado após a ação
                    unmountOnBlur: true, 
                }}
            />
        </Drawer.Navigator>
      </NavigationContainer>
    </SafeAreaProvider>
  );
}

const styles = StyleSheet.create({
    loadingContainer: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
    }
});