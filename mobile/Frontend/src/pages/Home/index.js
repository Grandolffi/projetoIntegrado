import React from 'react';
import { View, StyleSheet, Text, SafeAreaView, ScrollView, TouchableOpacity } from 'react-native';

import Header from '../../components/Header';
import User from '../../components/User';
import { useNavigation } from '@react-navigation/native';
import { MaterialCommunityIcons } from '@expo/vector-icons';

const VOLL_BLUE = "#0E3B5F";

export default function Home() {

  const navigation = useNavigation();

  return (
    <SafeAreaView style={Estilo.safeArea}>
      <Header />

      <ScrollView style={Estilo.scrollView}>
        <User nomeUsuario="Fernanda" />

        {/* Bloco de texto de boas-vindas */}
        <View style={Estilo.contentContainer}>
          <Text style={Estilo.welcomeTitle}>Bem-vindo(a) ao BIO Diagnóstico</Text>
        </View>

        <View style={Estilo.listContainer}>

          {/* BLOCO 1: Pacientes */}
          <TouchableOpacity
            style={Estilo.cardVollStyle}
            activeOpacity={0.8}
            onPress={() => navigation.navigate("ListaPacientes")}
          >
            <View style={Estilo.iconWrapperTransparent}>
              <MaterialCommunityIcons name="account-outline" size={45} color="#FFFFFF" />
            </View>
            <Text style={Estilo.cardTextWhite}>Pacientes</Text>
          </TouchableOpacity>

           {/* BLOCO 2: Solicitar Exame */}
          <TouchableOpacity
            style={Estilo.cardVollStyle}
            activeOpacity={0.8}
            onPress={() => navigation.navigate("SolicitarNovoExame")}
          >
            {/* O wrapper do ícone agora é transparente */}
            <View style={Estilo.iconWrapperTransparent}>
              <MaterialCommunityIcons name="flask-outline" size={45} color="#FFFFFF" />
            </View>
            <Text style={Estilo.cardTextWhite}>Solicitar Exame</Text>
          </TouchableOpacity>

        </View>
      </ScrollView>
    </SafeAreaView>
  );
}

const Estilo = StyleSheet.create({
  safeArea: {
    flex: 1,
    backgroundColor: '#FFFFFF',
  },
  scrollView: {
    flex: 1,
  },

  // 2. Área de Texto Superior (Boas-vindas)
  contentContainer: {
    marginHorizontal: 20,
    marginTop: 30,
    marginBottom: 20,
    alignItems: 'center',
    backgroundColor: 'transparent',
  },

  welcomeTitle: {
    fontSize: 22,
    fontWeight: 'bold',
    color: VOLL_BLUE, // Azul escuro
    marginBottom: 10,
    textAlign: 'center',
  },

  // 3. Container da Lista de Botões
  listContainer: {
    marginHorizontal: 20,
    marginTop: 10,
    marginBottom: 30,
  },

  // 4. Estilo do Cartão (Botão Grande Azul)
  cardVollStyle: {
    backgroundColor: VOLL_BLUE, 
    paddingVertical: 30,       
    borderRadius: 10, 
    alignItems: "center",
    justifyContent: "center",
    marginBottom: 20,           
    width: '100%',
    
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 3,
    elevation: 2,
  },

  // 5. Ícones
  iconWrapperTransparent: {
    alignItems: "center",
    justifyContent: "center",
    marginBottom: 10,          
    backgroundColor: 'transparent', 
  },

  // 6. Texto dentro do Cartão
  cardTextWhite: {
    color: "#FFFFFF",
    fontSize: 18, 
    fontWeight: "bold", 
    textAlign: "center",
  },
});