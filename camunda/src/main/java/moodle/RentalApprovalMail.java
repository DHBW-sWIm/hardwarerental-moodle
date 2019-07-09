package moodle;

import org.camunda.bpm.engine.delegate.DelegateExecution;
import org.camunda.bpm.engine.delegate.JavaDelegate;
import org.joda.time.format.ISODateTimeFormat;

import javax.mail.MessagingException;
import java.io.IOException;
import java.sql.Timestamp;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.Date;

public class RentalApprovalMail implements JavaDelegate {

    public void execute(DelegateExecution execution) throws IOException {

        // Get Camunda variables to work with them
        String stdntName = (String) execution.getVariable("stdnt_name");
        String stdntMatnr = (String) execution.getVariable("stdnt_matnr");
        String stdntResource = (String) execution.getVariable("stdnt_resource");
        String stdntLength = (String) execution.getVariable("stdnt_length");
        Long stdntQuantity = (Long) execution.getVariable("stdnt_quantity");
        String pickupplace = (String) execution.getVariable("pickupplace");
        Date pickupdate = (Date) execution.getVariable("pickupdate");

        // formatting and converting the date - plase use LocalDateTime for Date or Time related stuff
        DateTimeFormatter formatter = DateTimeFormatter.ofPattern("dd.MM.yyyy");
        LocalDateTime localSickUntil = new Timestamp(pickupdate.getTime()).toLocalDateTime();
        String string_pickupdate = localSickUntil.format(formatter);

        // Fill Mail with information
        String content = "<h1> Ihr Ausleihantrag wurde genehmigt! </h1>"
                + "<p>Student: " + stdntName + "</p>"
                + "<p>Matrikel-Nr.: " + stdntMatnr + "</p>"
                + "<p>Resource: " + stdntResource + "</p>"
                + "<p>Anzahl: " + stdntQuantity.toString() + "</p>"
                + "<p>Länge: " + stdntLength + "</p>"
                + "<p>Abholdatum: " + string_pickupdate + "</p>"
                + "<p>Abholort: " + pickupplace + "</p>"
                + "<p>Wir freuen uns schon auf Ihren nächste Ausleihantrag</p>";
        String receiver = "s162043@student.dhbw-mannheim.de";
        String subject = "Der Ausleihantrag wurde genehmigt!";

        try {
            Mail.send(receiver, subject, content);
        } catch (MessagingException e) {
            CamundaLogger.log(execution, e, RentalApprovalMail.class.getName());
        }
    }
}
