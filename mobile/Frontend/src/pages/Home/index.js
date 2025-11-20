import React from 'react';
import { View, StyleSheet, Text, SafeAreaView, ScrollView } from 'react-native';


import Header from '../../components/Header';
import User from '../../components/User';

export default function Home() {

  return (
    <SafeAreaView style={Estilo.safeArea}>
        <Header />
  
      <ScrollView style={Estilo.scrollView}>
        <User nomeUsuario="Fernanda" />

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
  },
  contentContainer: {
        marginHorizontal: 20, 
        padding: 20,
        backgroundColor: '#fff', 
        borderRadius: 10,
        
        // Estilos de sombra para dar efeito "flutuante"
        shadowColor: "#000",
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.1,
        shadowRadius: 3.84,
        elevation: 5,
        
        alignItems: 'center',
        marginTop: 15, 
    },
});
