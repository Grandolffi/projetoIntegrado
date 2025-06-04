import java.util.ArrayList;
import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;

public class Main {
    public static void main(String[] args) {
        ArrayList<Pessoa> pessoas = new ArrayList<>();

        Pessoa p1 = new Pessoa("Lucas Almeida", "12345678901", "120398", "lucas.almeida@gmail.com", "Maria Almeida", "11987654321", "masculino");
        Pessoa p2 = new Pessoa("Juliana Costa", "23456789012", "150690", "juliana.costa@hotmail.com", "Ana Costa", "21998765432", "feminino");
        Pessoa p3 = new Pessoa("Rafael Souza", "34567890123", "200184", "rafael.souza@yahoo.com", "Claudia Souza", "31991234567", "masculino");
        Pessoa p4 = new Pessoa("Fernanda Lima", "45678901234", "090795", "fernanda.lima@gmail.com", "Patricia Lima", "41992345678", "feminino");
        Pessoa p5 = new Pessoa("Bruno Oliveira", "56789012345", "250692", "bruno.oliveira@outlook.com", "Sandra Oliveira", "51993456789", "masculino");
        Pessoa p6 = new Pessoa("Mariana Rocha", "67890123456", "180883", "mariana.rocha@gmail.com", "Lucia Rocha", "61994567890", "feminino");
        Pessoa p7 = new Pessoa("Carlos Pereira", "78901234567", "100376", "carlos.pereira@hotmail.com", "Helena Pereira", "71995678901", "masculino");
        Pessoa p8 = new Pessoa("Patricia Gomes", "89012345678", "040990", "patricia.gomes@yahoo.com", "Rosa Gomes", "81996789012", "feminino");
        Pessoa p9 = new Pessoa("Eduardo Fernandes", "90123456789", "220580", "eduardo.fernandes@gmail.com", "Beatriz Fernandes", "92997890123", "masculino");
        Pessoa p10 = new Pessoa("Camila Ribeiro", "11223344556", "140792", "camila.ribeiro@outlook.com", "Marta Ribeiro", "83998901234", "feminino");

        Pessoa p11 = new Pessoa("Felipe Martins", "22334455667", "101295", "felipe.martins@gmail.com", "Teresa Martins", "11999012345", "masculino");
        Pessoa p12 = new Pessoa("Aline Mendes", "33445566778", "171087", "aline.mendes@hotmail.com", "Regina Mendes", "21990123456", "feminino");
        Pessoa p13 = new Pessoa("Diego Castro", "44556677889", "080593", "diego.castro@yahoo.com", "Elaine Castro", "31991234567", "masculino");
        Pessoa p14 = new Pessoa("Larissa Silva", "55667788990", "230284", "larissa.silva@gmail.com", "Juliana Silva", "41992345678", "feminino");
        Pessoa p15 = new Pessoa("Marcelo Barbosa", "66778899001", "110692", "marcelo.barbosa@outlook.com", "Gloria Barbosa", "51993456789", "masculino");
        Pessoa p16 = new Pessoa("Tatiane Cardoso", "77889900112", "060884", "tatiane.cardoso@gmail.com", "Cecilia Cardoso", "61994567890", "feminino");
        Pessoa p17 = new Pessoa("André Nascimento", "88990011223", "190378", "andre.nascimento@hotmail.com", "Natalia Nascimento", "71995678901", "masculino");
        Pessoa p18 = new Pessoa("Renata Cunha", "99001122334", "020191", "renata.cunha@yahoo.com", "Ivone Cunha", "81996789012", "feminino");
        Pessoa p19 = new Pessoa("Thiago Teixeira", "10111213141", "270580", "thiago.teixeira@gmail.com", "Angela Teixeira", "92997890123", "masculino");
        Pessoa p20 = new Pessoa("Bianca Lopes", "12131415161", "310792", "bianca.lopes@outlook.com", "Vera Lopes", "83998901234", "feminino");

        Pessoa p21 = new Pessoa("Gustavo Araujo", "13141516171", "070695", "gustavo.araujo@gmail.com", "Marcia Araujo", "11999012345", "masculino");
        Pessoa p22 = new Pessoa("Isabela Martins", "14151617181", "221187", "isabela.martins@hotmail.com", "Silvia Martins", "21990123456", "feminino");
        Pessoa p23 = new Pessoa("Pedro Henrique", "15161718191", "120293", "pedro.henrique@yahoo.com", "Josefa Henrique", "31991234567", "masculino");
        Pessoa p24 = new Pessoa("Amanda Freitas", "16171819202", "160284", "amanda.freitas@gmail.com", "Cristina Freitas", "41992345678", "feminino");
        Pessoa p25 = new Pessoa("Leandro Melo", "17181920212", "090692", "leandro.melo@outlook.com", "Marinalva Melo", "51993456789", "masculino");
        Pessoa p26 = new Pessoa("Leticia Duarte", "18192021222", "300184", "leticia.duarte@gmail.com", "Paula Duarte", "61994567890", "feminino");
        Pessoa p27 = new Pessoa("Rodrigo Lima", "19202122232", "050376", "rodrigo.lima@hotmail.com", "Tania Lima", "71995678901", "masculino");
        Pessoa p28 = new Pessoa("Vanessa Souza", "20212223242", "101291", "vanessa.souza@yahoo.com", "Aparecida Souza", "81996789012", "feminino");
        Pessoa p29 = new Pessoa("Fábio Reis", "21222324352", "210580", "fabio.reis@gmail.com", "Sueli Reis", "92997890123", "masculino");
        Pessoa p30 = new Pessoa("Carla Monteiro", "22232425362", "280792", "carla.monteiro@outlook.com", "Neide Monteiro", "83998901234", "feminino");



        pessoas.add(p1);
        pessoas.add(p2);
        pessoas.add(p3);
        pessoas.add(p4);
        pessoas.add(p5);
        pessoas.add(p6);
        pessoas.add(p7);
        pessoas.add(p8);
        pessoas.add(p9);
        pessoas.add(p10);
        pessoas.add(p11);
        pessoas.add(p12);
        pessoas.add(p13);
        pessoas.add(p14);
        pessoas.add(p15);
        pessoas.add(p16);
        pessoas.add(p17);
        pessoas.add(p18);
        pessoas.add(p19);
        pessoas.add(p20);
        pessoas.add(p21);
        pessoas.add(p22);
        pessoas.add(p23);
        pessoas.add(p24);
        pessoas.add(p25);
        pessoas.add(p26);
        pessoas.add(p27);
        pessoas.add(p28);
        pessoas.add(p29);
        pessoas.add(p30);

        try {
            FileWriter file = new FileWriter("pessoas.sql");
            BufferedWriter buffWriter = new BufferedWriter(file);

            for (Pessoa p : pessoas) {
                String sql = String.format(
                        "INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s');",
                        p.getNome(), p.getCpf(), p.getDtnasc(), p.getEmail(), p.getNomemae(), p.getNumcelular(), p.getGenero()
                );

                buffWriter.write(sql);
                buffWriter.newLine();
            }
            buffWriter.close();
            System.out.println("Arquivo SQL gerado com sucesso!");
        }catch(IOException e){
            System.out.println("Erro ao escrever o arquivo: " + e.getMessage());
        }

    }
}