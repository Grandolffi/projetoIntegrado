import React from 'react';
import { View, StyleSheet, Text, SafeAreaView, ScrollView } from 'react-native';
import { useNavigation } from '@react-navigation/native';
import { Feather } from '@expo/vector-icons';

import Header from '../../components/Header';
import User from '../../components/User';
import PageAtual from '../../components/PageAtual';

// Componente de botão de menu customizado (Hamburguer)
const MenuButton = ({ onPress }) => (
  <View style={Estilo.menuButtonContainer}>
    <Feather 
      name="menu" 
      size={30} 
      color="#fff"
      onPress={onPress} // Ação para abrir o menu: navigation.openDrawer()
    />
  </View>
);

export default function Home() {
  // Hook do React Navigation para acessar a navegação
  const navigation = useNavigation();

  return (
    <SafeAreaView style={Estilo.safeArea}>
      
      {/* Container principal para o Header e o botão de Menu sobreposto */}
      <View style={Estilo.headerContainer}>
        <Header />
        
        {/* Adiciona o botão de menu (Hambúrguer) no canto superior esquerdo do Header */}
        <MenuButton onPress={() => navigation.openDrawer()} />
      </View>


      <ScrollView style={Estilo.scrollView}>
        {/* 1. Bloco de Informações do Usuário */}
        <User nomeUsuario="Fernanda" />
        
        {/* 2. Bloco da Página Atual: "MENU" (Banner verde) */}
        <PageAtual Pageatual="MENU" />

        {/* 3. Área principal para o Conteúdo da Home */}
        <View style={Estilo.contentContainer}>
          <Text style={Estilo.welcomeTitle}>Bem-vindo(a) ao BIO Diagnóstico</Text>
          <Text style={Estilo.instructions}>
            Use o ícone ☰ no canto superior esquerdo para acessar as opções do Menu.
          </Text>
        </View>

      </ScrollView>

    </SafeAreaView>
  );
}

const Estilo = StyleSheet.create({
  safeArea: {
    flex: 1,
    backgroundColor: '#f0f0f0',
  },
  scrollView: {
    flex: 1,
  },
  headerContainer: {
    // O Header já tem o background '#0A212F'
  },
  menuButtonContainer: {
    // Posiciona o ícone do menu de forma absoluta sobre o Header
    position: 'absolute',
    top: 50, 
    left: 10,
    zIndex: 10, 
    padding: 10,
  },
  contentContainer: {
    padding: 20,
    alignItems: 'center',
  },
  welcomeTitle: {
    fontSize: 22,
    fontWeight: 'bold',
    color: '#0A212F',
    marginBottom: 10,
    marginTop: 20,
  },
  instructions: {
    fontSize: 16,
    color: '#586A76',
    textAlign: 'center',
    lineHeight: 24,
  }
});
