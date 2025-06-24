import java.util.ArrayList;
import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;

public class Main {
    public static void main(String[] args) {
        ArrayList<Pessoa> pacientes = new ArrayList<>();

        Pessoa p1 = new Pessoa("Lucas Almeida", "12345678901", "1998-03-12", "lucas.almeida@gmail.com", "Maria Almeida", "11987654321", "masculino");
        Pessoa p2 = new Pessoa("Juliana Costa", "23456789012", "1990-06-15", "juliana.costa@hotmail.com", "Ana Costa", "21998765432", "feminino");
        Pessoa p3 = new Pessoa("Rafael Souza", "34567890123", "1984-01-20", "rafael.souza@yahoo.com", "Claudia Souza", "31991234567", "masculino");
        Pessoa p4 = new Pessoa("Fernanda Lima", "45678901234", "1995-07-09", "fernanda.lima@gmail.com", "Patricia Lima", "41992345678", "feminino");
        Pessoa p5 = new Pessoa("Bruno Oliveira", "56789012345", "1992-06-25", "bruno.oliveira@outlook.com", "Sandra Oliveira", "51993456789", "masculino");
        Pessoa p6 = new Pessoa("Mariana Rocha", "67890123456", "1983-08-18", "mariana.rocha@gmail.com", "Lucia Rocha", "61994567890", "feminino");
        Pessoa p7 = new Pessoa("Carlos Pereira", "78901234567", "1976-03-10", "carlos.pereira@hotmail.com", "Helena Pereira", "71995678901", "masculino");
        Pessoa p8 = new Pessoa("Patricia Gomes", "89012345678", "1990-09-04", "patricia.gomes@yahoo.com", "Rosa Gomes", "81996789012", "feminino");
        Pessoa p9 = new Pessoa("Eduardo Fernandes", "90123456789", "1980-05-22", "eduardo.fernandes@gmail.com", "Beatriz Fernandes", "92997890123", "masculino");
        Pessoa p10 = new Pessoa("Camila Ribeiro", "11223344556", "1992-07-14", "camila.ribeiro@outlook.com", "Marta Ribeiro", "83998901234", "feminino");

        Pessoa p11 = new Pessoa("Felipe Martins", "22334455667", "1995-12-10", "felipe.martins@gmail.com", "Teresa Martins", "11999012345", "masculino");
        Pessoa p12 = new Pessoa("Aline Mendes", "33445566778", "1987-10-17", "aline.mendes@hotmail.com", "Regina Mendes", "21990123456", "feminino");
        Pessoa p13 = new Pessoa("Diego Castro", "44556677889", "1993-05-08", "diego.castro@yahoo.com", "Elaine Castro", "31991234567", "masculino");
        Pessoa p14 = new Pessoa("Larissa Silva", "55667788990", "1984-02-23", "larissa.silva@gmail.com", "Juliana Silva", "41992345678", "feminino");
        Pessoa p15 = new Pessoa("Marcelo Barbosa", "66778899001", "1992-06-11", "marcelo.barbosa@outlook.com", "Gloria Barbosa", "51993456789", "masculino");
        Pessoa p16 = new Pessoa("Tatiane Cardoso", "77889900112", "1984-08-06", "tatiane.cardoso@gmail.com", "Cecilia Cardoso", "61994567890", "feminino");
        Pessoa p17 = new Pessoa("André Nascimento", "88990011223", "1978-03-19", "andre.nascimento@hotmail.com", "Natalia Nascimento", "71995678901", "masculino");
        Pessoa p18 = new Pessoa("Renata Cunha", "99001122334", "1991-01-02", "renata.cunha@yahoo.com", "Ivone Cunha", "81996789012", "feminino");
        Pessoa p19 = new Pessoa("Thiago Teixeira", "10111213141", "1980-05-27", "thiago.teixeira@gmail.com", "Angela Teixeira", "92997890123", "masculino");
        Pessoa p20 = new Pessoa("Bianca Lopes", "12131415161", "1992-07-31", "bianca.lopes@outlook.com", "Vera Lopes", "83998901234", "feminino");

        Pessoa p21 = new Pessoa("Gustavo Araujo", "13141516171", "1995-06-07", "gustavo.araujo@gmail.com", "Marcia Araujo", "11999012345", "masculino");
        Pessoa p22 = new Pessoa("Isabela Martins", "14151617181", "1987-11-22", "isabela.martins@hotmail.com", "Silvia Martins", "21990123456", "feminino");
        Pessoa p23 = new Pessoa("Pedro Henrique", "15161718191", "1993-02-12", "pedro.henrique@yahoo.com", "Josefa Henrique", "31991234567", "masculino");
        Pessoa p24 = new Pessoa("Amanda Freitas", "16171819202", "1984-04-16", "amanda.freitas@gmail.com", "Cristina Freitas", "41992345678", "feminino");
        Pessoa p25 = new Pessoa("Leandro Melo", "17181920212", "1992-06-09", "leandro.melo@outlook.com", "Marinalva Melo", "51993456789", "masculino");
        Pessoa p26 = new Pessoa("Leticia Duarte", "18192021222", "1984-01-30", "leticia.duarte@gmail.com", "Paula Duarte", "61994567890", "feminino");
        Pessoa p27 = new Pessoa("Rodrigo Lima", "19202122232", "1976-03-05", "rodrigo.lima@hotmail.com", "Tania Lima", "71995678901", "masculino");
        Pessoa p28 = new Pessoa("Vanessa Souza", "20212223242", "1991-12-10", "vanessa.souza@yahoo.com", "Aparecida Souza", "81996789012", "feminino");
        Pessoa p29 = new Pessoa("Fábio Reis", "21222324352", "1980-05-21", "fabio.reis@gmail.com", "Sueli Reis", "92997890123", "masculino");
        Pessoa p30 = new Pessoa("Carla Monteiro", "22232425362", "1992-07-28", "carla.monteiro@outlook.com", "Neide Monteiro", "83998901234", "feminino");



        pacientes.add(p1);
        pacientes.add(p2);
        pacientes.add(p3);
        pacientes.add(p4);
        pacientes.add(p5);
        pacientes.add(p6);
        pacientes.add(p7);
        pacientes.add(p8);
        pacientes.add(p9);
        pacientes.add(p10);
        pacientes.add(p11);
        pacientes.add(p12);
        pacientes.add(p13);
        pacientes.add(p14);
        pacientes.add(p15);
        pacientes.add(p16);
        pacientes.add(p17);
        pacientes.add(p18);
        pacientes.add(p19);
        pacientes.add(p20);
        pacientes.add(p21);
        pacientes.add(p22);
        pacientes.add(p23);
        pacientes.add(p24);
        pacientes.add(p25);
        pacientes.add(p26);
        pacientes.add(p27);
        pacientes.add(p28);
        pacientes.add(p29);
        pacientes.add(p30);

        try {
            FileWriter file = new FileWriter("pacientes.sql");
            BufferedWriter buffWriter = new BufferedWriter(file);

            for (Pessoa p : pacientes) {
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