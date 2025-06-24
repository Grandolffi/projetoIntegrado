import java.time.LocalDate;
import java.time.format.DateTimeFormatter;
import java.time.format.DateTimeParseException;

public class Pessoa {

    private String nome;
    private String cpf;
    private String dtnasc;
    private String email;
    private String nomemae;
    private String numcelular;
    private String genero;

    public Pessoa(){};

    public Pessoa(String nome, String cpf, String dtnasc, String email, String nomemae, String numcelular, String genero){
            this.nome = nome;
            this.cpf = cpf;
            this.dtnasc = dtnasc;
            this.email = email;
            this.nomemae = nomemae;
            this.numcelular = numcelular;
            this.genero = genero;
    }

    private String converteData(String data_ddMMyy) { //FEITO COM IA
        try {
            DateTimeFormatter formatterEntrada = DateTimeFormatter.ofPattern("ddMMyy");
            LocalDate data = LocalDate.parse(data_ddMMyy, formatterEntrada);

            // Ajusta o século para 1900 ou 2000 automaticamente
            // Caso precise tratar especificamente, pode usar Year.of(2000 + ano) se precisar

            DateTimeFormatter formatterSaida = DateTimeFormatter.ofPattern("yyyy-MM-dd");
            return data.format(formatterSaida);
        } catch (DateTimeParseException e) {
            System.err.println("Formato de data inválido: " + data_ddMMyy);
            return null; // ou lance exceção, depende do seu fluxo
        }
    }

    public String getNome() {
        return nome;
    }

    public void setNome(String nome) {
        this.nome = nome;
    }

    public String getCpf() {
        return cpf;
    }

    public void setCpf(String cpf) {
        this.cpf = cpf;
    }

    public String getDtnasc() {
        return dtnasc;
    }

    public void setDtnasc(String dtnasc) {
        this.dtnasc = dtnasc;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getNomemae() {
        return nomemae;
    }

    public void setNomemae(String nomemae) {
        this.nomemae = nomemae;
    }

    public String getNumcelular() {
        return numcelular;
    }

    public void setNumcelular(String numcelular) {
        this.numcelular = numcelular;
    }

    public String getGenero() {
        return genero;
    }

    public void setGenero(String genero) {
        this.genero = genero;
    }

    @Override
    public String toString() {
        return "Pessoa{" +
                "nome='" + nome + '\'' +
                ", cpf='" + cpf + '\'' +
                ", dtnasc='" + dtnasc + '\'' +
                ", email='" + email + '\'' +
                ", nomemae='" + nomemae + '\'' +
                ", numcelular='" + numcelular + '\'' +
                ", genero='" + genero + '\'' +
                '}';
    }
}
