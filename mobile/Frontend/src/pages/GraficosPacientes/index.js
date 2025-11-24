import React, { useState, useEffect } from "react";
import { View, Text, StyleSheet, ScrollView, TouchableOpacity, Image } from "react-native";
import { Feather } from "@expo/vector-icons";

import Header from "../../components/Header";
import User from "../../components/User";
import PageAtual from "../../components/PageAtual";

export default function GraficosPacientes({ navigation }) {
  const [listaImagens, setListaImagens] = useState([]);

  useEffect(() => {
    carregarImagens();
  }, []);

  const carregarImagens = async () => {
    try {
      const resp = await fetch("http://192.168.15.118:3000/lista-graficos");
      const dados = await resp.json();

      console.log("🔵 Imagens recebidas:", dados);

      setListaImagens(dados);
    } catch (error) {
      console.log("Erro ao carregar imagens:", error);
    }
  };

  const abrirImagem = (arquivo) => {
    navigation.navigate("VisualizarGrafico", { arquivo });
  };

  return (
    <View style={styles.container}>
      <Header />
      <User nomeUsuario="Fernanda" />
      <PageAtual Pageatual="Gráficos dos Pacientes" />

      <ScrollView contentContainerStyle={styles.contentContainer}>
        {listaImagens.length === 0 ? (
          <Text style={styles.empty}>Nenhuma imagem encontrada.</Text>
        ) : (
          listaImagens.map((img) => (
            <TouchableOpacity
              key={img}
              style={styles.card}
              onPress={() => abrirImagem(img)}
            >
              <Image
                    source={{ uri: `http://192.168.15.118:3000/graficos_pacientes/${img}` }}
                     style={styles.thumbnail}
                    onError={(e) => console.log("ERRO NA IMG:", e.nativeEvent.error)}
                />
               

              <View style={styles.cardInfo}>
                <Text style={styles.cardTitle}>{img}</Text>
                <Feather name="eye" size={22} color="#0A212F" />
              </View>
            </TouchableOpacity>
          ))
        )}

        <View style={{ height: 120 }} />
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: "#f0f0f0" },
  contentContainer: { padding: 20, paddingBottom: 120 },

  card: {
    backgroundColor: "#fff",
    padding: 15,
    borderRadius: 12,
    marginBottom: 20,
    shadowColor: "#000",
    shadowOpacity: 0.15,
    shadowRadius: 6,
    shadowOffset: { width: 0, height: 3 },
    elevation: 5,
  },

  thumbnail: {
    width: "100%",
    height: 220,
    borderRadius: 10,
    marginBottom: 12,
    backgroundColor: "#eee",
  },

  cardInfo: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
  },

  cardTitle: {
    fontSize: 14,
    fontWeight: "600",
    color: "#0A212F",
  },

  empty: {
    textAlign: "center",
    fontSize: 16,
    color: "#777",
    marginTop: 30,
  },
});
