import React from 'react';
import { View, StyleSheet, Text, SafeAreaView, ScrollView } from 'react-native';

import Header from '../../components/Header';
import User from '../../components/User';
import PageAtual from '../../components/PageAtual';

// Componente de botão de menu customizado (Hamburguer)

export default function Home() {
  // Hook do React Navigation para acessar a navegação
  return (
    <SafeAreaView style={Estilo.safeArea}>
        <Header />
  
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
