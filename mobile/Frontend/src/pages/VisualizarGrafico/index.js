import React from "react";
import { View, Text, StyleSheet, Image, TouchableOpacity } from "react-native";
import { Feather } from "@expo/vector-icons";

export default function VisualizarGrafico({ route, navigation }) {
  const { arquivo } = route.params;

  return (
    <View style={styles.container}>
      <TouchableOpacity style={styles.voltar} onPress={() => navigation.goBack()}>
        <Feather name="arrow-left" size={26} color="#fff" />
      </TouchableOpacity>

      <Text style={styles.titulo}>{arquivo}</Text>

      <Image
        source={{ uri: `http://192.168.15.118:3000/graficos_pacientes/${arquivo}` }}
        style={styles.imagem}
        resizeMode="contain"
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { 
    flex: 1, 
    backgroundColor: "#0A212F", 
    paddingTop: 40,
    paddingHorizontal: 10
  },

  voltar: { marginLeft: 10, marginBottom: 10 },

  titulo: {
    color: "#fff",
    fontSize: 18,
    textAlign: "center",
    marginBottom: 10,
    fontWeight: "bold",
  },

  imagem: {
    width: "100%",
    height: "85%",
    borderRadius: 10,
    backgroundColor: "#fff",
    padding: 10,
  },
});
