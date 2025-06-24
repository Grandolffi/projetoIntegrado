public class Paciente extends Pessoa{
    private boolean doencaCronica;

    public Paciente(){}

    public Paciente(String nome, String cpf, String dtnasc, String email, String nomemae, String numcelular, String genero, boolean doencaCronia){
        super(nome, cpf, dtnasc, email, nomemae, numcelular, genero);
        this.doencaCronica = doencaCronia;
    }

    public boolean isDoencaCronica() {
        return doencaCronica;
    }

    public void setDoencaCronica(boolean doencaCronica) {
        this.doencaCronica = doencaCronica;
    }

    @Override
    public String toString() {
        return "Paciente{" +
                "doencaCronica=" + doencaCronica +
                '}';
    }
}
