package proj;

import javax.swing.*;
import java.awt.*;
import java.awt.event.*;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import org.mindrot.jbcrypt.BCrypt;

public class LoginPage extends JFrame {
    private JTextField txtUsername;
    private JPasswordField txtPassword;
    private JButton btnLogin;
    private JToggleButton togglePassword;
    private ImageIcon eyeOpenIcon;
    private ImageIcon eyeClosedIcon;

    public LoginPage() {
        setTitle("Connexion");
        setSize(400, 210);
        setDefaultCloseOperation(EXIT_ON_CLOSE);
        initUI();
        setLocationRelativeTo(null); 
    }

    private void initUI() {
        UIManager.put("Panel.background", new Color(238, 238, 238));
        UIManager.put("Label.font", new Font("Segoe UI", Font.PLAIN, 14));
        UIManager.put("Button.font", new Font("Segoe UI", Font.BOLD, 14));
        UIManager.put("TextField.font", new Font("Segoe UI", Font.PLAIN, 14));
        UIManager.put("PasswordField.font", new Font("Segoe UI", Font.PLAIN, 14));
        
 
        JPanel mainPanel = new JPanel(new BorderLayout(10, 10));
        mainPanel.setBorder(BorderFactory.createEmptyBorder(30, 30, 30, 30));
        
        JPanel fieldsPanel = new JPanel();
        fieldsPanel.setLayout(new BoxLayout(fieldsPanel, BoxLayout.Y_AXIS));

        JLabel userLabel = new JLabel("Nom d'utilisateur:");
        txtUsername = new JTextField();
     

        JLabel passLabel = new JLabel("Mot de passe:");
        txtPassword = new JPasswordField();


        int fieldWidth = 300;
        int fieldHeight = txtUsername.getPreferredSize().height; 

        txtUsername.setMaximumSize(new Dimension(fieldWidth, fieldHeight));
        txtPassword.setMaximumSize(new Dimension(fieldWidth, fieldHeight));
        
        initIcons();
        togglePassword = new JToggleButton(eyeClosedIcon, false);
        togglePassword.setPreferredSize(new Dimension(24, 24));
        togglePassword.setBorder(BorderFactory.createEmptyBorder());
        togglePassword.setContentAreaFilled(false);
        togglePassword.setFocusPainted(false);
        togglePassword.addActionListener(this::togglePasswordVisibility);
        
        JPanel userPanel = new JPanel();
        userPanel.setLayout(new BoxLayout(userPanel, BoxLayout.LINE_AXIS));
        userPanel.add(userLabel);
        userPanel.add(Box.createRigidArea(new Dimension(5, 0))); 
        userPanel.add(txtUsername);

        JPanel passPanel = new JPanel();
        passPanel.setLayout(new BoxLayout(passPanel, BoxLayout.LINE_AXIS));
        passPanel.add(passLabel);
        passPanel.add(Box.createRigidArea(new Dimension(5, 0))); 
        passPanel.add(txtPassword);
        passPanel.add(togglePassword);

        fieldsPanel.add(userPanel);
        fieldsPanel.add(Box.createRigidArea(new Dimension(0, 15)));
        fieldsPanel.add(passPanel);

        btnLogin = new JButton("Se connecter");
        btnLogin.setAlignmentX(Component.CENTER_ALIGNMENT);
        btnLogin.addActionListener(this::loginAction);
        
        mainPanel.add(fieldsPanel, BorderLayout.CENTER);
        mainPanel.add(btnLogin, BorderLayout.SOUTH);
        

        add(mainPanel);

        getRootPane().setDefaultButton(btnLogin);
    }



    private void initIcons() {
    	eyeOpenIcon = createIcon("eye.png", 20, 20);
    	eyeClosedIcon = createIcon("eye2.png", 20, 20);
    }

    private ImageIcon createIcon(String iconName, int width, int height) {
        String path = "icons/" + iconName;
        java.net.URL imgURL = getClass().getResource(path);
        if (imgURL != null) {
            ImageIcon icon = new ImageIcon(imgURL);
            Image image = icon.getImage();
            Image newimg = image.getScaledInstance(width, height,  java.awt.Image.SCALE_SMOOTH);
            return new ImageIcon(newimg);
        } else {
            System.err.println("Fichier introuvable: " + path);
            return null;
        }
    }


    private void togglePasswordVisibility(ActionEvent e) {
        if (togglePassword.isSelected()) {
            txtPassword.setEchoChar((char) 0);
            togglePassword.setIcon(eyeOpenIcon);
        } else {
            txtPassword.setEchoChar('•');
            togglePassword.setIcon(eyeClosedIcon);
        }
    }

    private void loginAction(ActionEvent e) {
        if (authenticate(txtUsername.getText(), new String(txtPassword.getPassword()))) {
            dispose();
            new HomePage(txtUsername.getText()).setVisible(true);
        } else {
            JOptionPane.showMessageDialog(this, "Nom d'utilisateur ou mot de passe incorrect.", "Échec de la connexion", JOptionPane.ERROR_MESSAGE);
        }
    }
    
    public static boolean authenticate(String username, String password) {
        try (Connection conn = DBConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement("SELECT mdpUtil FROM utilisateur WHERE identifiantUtil = ?")) {

            stmt.setString(1, username);

            try (ResultSet rs = stmt.executeQuery()) {
                if (rs.next()) {
                    String hashedPassword = rs.getString("mdpUtil");
                    if (hashedPassword.startsWith("$2y$")) {
                        hashedPassword = "$2a$" + hashedPassword.substring(4);
                    }
                    return BCrypt.checkpw(password, hashedPassword);
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
            return false;
        }
        return false;
    }

    public static void main(String[] args) {
        SwingUtilities.invokeLater(() -> new LoginPage().setVisible(true));
    }
}

