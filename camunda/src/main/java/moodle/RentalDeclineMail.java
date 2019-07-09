package moodle;

import org.camunda.bpm.engine.delegate.DelegateExecution;
import org.camunda.bpm.engine.delegate.JavaDelegate;

import javax.mail.MessagingException;
import java.io.IOException;

public class RentalDeclineMail implements JavaDelegate {

    public void execute(DelegateExecution execution) throws IOException {

        // Get Camunda variables to work with them
        String stdntName = (String) execution.getVariable("stdnt_name");
        String stdntMatnr = (String) execution.getVariable("stdnt_matnr");
        String stdntResource = (String) execution.getVariable("stdnt_resource");
        Long stdntQuantity = (Long) execution.getVariable("stdnt_quantity");
        String comment = (String) execution.getVariable("comment");

        // Fill Mail with information
        String content = "<h1> Ihr Ausleihantrag wurde leider abgelehnt! </h1>"
                + "<p>Student: " + stdntName + "</p>"
                + "<p>Matrikel-Nr.: " + stdntMatnr + "</p>"
                + "<p>Resource: " + stdntResource + "</p>"
                + "<p>Anzahl: " + stdntQuantity.toString() + "</p>"
                + "<p>Kommentar: " + comment.toString() + "</p>"
                + "<p>Wir freuen uns trotzdem schon auf Ihren nächste Ausleihantrag</p>";
        String receiver = "s162043@student.dhbw-mannheim.de";
        String subject = "Der Ausleihantrag wurde abgelehnt!";

        try {
            Mail.send(receiver, subject, content);
        } catch (MessagingException e) {
            CamundaLogger.log(execution, e, RentalDeclineMail.class.getName());
        }
    }
}
