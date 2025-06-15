import org.junit.jupiter.api.AfterAll;
import org.junit.jupiter.api.BeforeAll;
import org.junit.jupiter.api.Test;
import org.openqa.selenium.*;
import org.junit.Assert;
import org.openqa.selenium.bidi.log.Log;
import org.openqa.selenium.chrome.ChromeDriver;
import org.openqa.selenium.chrome.ChromeOptions;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.Select;
import org.openqa.selenium.support.ui.WebDriverWait;
import util.CookieUtils;

import java.io.*;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.time.Duration;
import java.util.Date;
import java.util.Set;

import static org.junit.jupiter.api.Assertions.assertEquals;
public class GameGear {
    private static WebDriver webDriver;
    private static String baseUrl;


    @BeforeAll
    public static void setUp() {
        System.setProperty("webdriver.chrome.driver", "/Users/muhamadassaad/chromedriver-mac-x64/chromedriver"); // Loading ChromeDriver
        ChromeOptions options = new ChromeOptions(); // Creating Option instances
        baseUrl = "https://web-app-vildan-2ynmk.ondigitalocean.app/"; // Link we use!
        options.addArguments("--remote-allow-origins=*");
        webDriver = new ChromeDriver(options);
        webDriver.manage().window().maximize();
    }
    @Test
    public void testLoadCookies() {
        webDriver.get(baseUrl);
        webDriver.manage().window().maximize();

        // Load cookies
        CookieUtils.loadCookies(webDriver, "cookies.data");

        // Perform your test steps after cookies are loaded
        System.out.println("Cookies loaded successfully!");
    }


    @AfterAll
    public static void tearDown() {
        // Close the browser
        if (webDriver != null) {
            webDriver.quit();
        }
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    //NEW LINE
    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    @Test // Logging in with pregiven information
    public void Login() throws InterruptedException {
        WebDriverWait wait = new WebDriverWait(webDriver, Duration.ofSeconds(2));
        webDriver.get(baseUrl);
        webDriver.manage().window().maximize();
        WebElement LoginIcon = wait.until(ExpectedConditions.elementToBeClickable(By.cssSelector("a[href*='#login']")));
        LoginIcon.click();
        WebElement email = webDriver.findElement(By.name("email"));
        WebElement password = webDriver.findElement(By.name("password"));
        email.sendKeys("vildanmuhamed@gmail.com");
        password.sendKeys("Burch123");
        WebElement loginButton = webDriver.findElement(By.cssSelector("button.btn.btn-primary.w-100"));
        loginButton.click();

    }
    @Test // Creating a new account
    public void Register() throws InterruptedException {
        WebDriverWait wait = new WebDriverWait(webDriver, Duration.ofSeconds(2));
        webDriver.get(baseUrl);
        webDriver.manage().window().maximize();
        WebElement LoginIcon = wait.until(ExpectedConditions.elementToBeClickable(By.cssSelector("a[href*='#register']")));
        LoginIcon.click();
        WebElement username = webDriver.findElement(By.id("registerUsername"));
        WebElement email = webDriver.findElement(By.id("registerEmail"));
        WebElement password = webDriver.findElement(By.id("registerPassword"));
        WebElement Confirm_Password = webDriver.findElement(By.id("confirmPassword"));
        WebElement roleDropdown = webDriver.findElement(By.id("role"));
        Select select = new Select(roleDropdown);
        select.selectByVisibleText("Admin");
        username.sendKeys("SVVTNajjaci");
        email.sendKeys("vildanmuhamed@gmail.com");
        password.sendKeys("Burch123");
        Confirm_Password.sendKeys("Burch123");
        Thread.sleep(2000);
        WebElement register = webDriver.findElement(By.cssSelector("button.btn.btn-primary.w-100"));
        register.click();
    }

    @Test // Checking all pages
    public void Traversal() throws InterruptedException {
        WebDriverWait wait = new WebDriverWait(webDriver, Duration.ofSeconds(2));
        webDriver.get(baseUrl);
        webDriver.manage().window().maximize();
        WebElement About = webDriver.findElement(By.cssSelector("a[href*='#about']"));
        About.click();
        Thread.sleep(1000);
        WebElement TitleAbout = webDriver.findElement(By.cssSelector("h2.fw-bolder"));
        assertEquals("WHO WE ARE?", TitleAbout.getText());
        WebElement ShopDrop = webDriver.findElement(By.id("shopDropdown"));
        ShopDrop.click();
        Thread.sleep(250);
        WebElement AllProducts = webDriver.findElement(By.cssSelector("a[href*='#products']"));
        AllProducts.click();
        // TODO
        WebElement Blog = webDriver.findElement(By.cssSelector("a[href*='#blog']"));
        Blog.click();
        Thread.sleep(250);
        WebElement BlogTitle = webDriver.findElement(By.cssSelector("h1.fw-bolder"));
        assertEquals("Gaming Insights", BlogTitle.getText());
        WebElement Contact = webDriver.findElement(By.cssSelector("a[href*='#contact']"));
        Contact.click();
        Thread.sleep(250);
        WebElement ContactTitle = webDriver.findElement(By.xpath("//h1[contains(text(),'Contact Us')]")
        );
        Thread.sleep(100);
        assertEquals("Contact Us", ContactTitle.getText());
        WebElement Cart = webDriver.findElement(By.cssSelector("form.d-flex"));
        Cart.click();
        Thread.sleep(250);
        WebElement ShopTitle = webDriver.findElement(By.xpath("//h2[contains(text(),'Your Shopping Cart')]"));
        assertEquals("Your Shopping Cart", ShopTitle.getText());

    }
    @Test // Checking Contact form
    public void Contact() throws InterruptedException {
        WebDriverWait wait = new WebDriverWait(webDriver, Duration.ofSeconds(2));
        webDriver.get(baseUrl);
        webDriver.manage().window().maximize();
        WebElement Contact = webDriver.findElement(By.cssSelector("a[href*='#contact']"));
        Contact.click();
        Thread.sleep(250);
        WebElement yourname = webDriver.findElement(By.id("name"));
        WebElement Email = webDriver.findElement(By.id("email"));
        WebElement roleDropdown = webDriver.findElement(By.id("subject"));
        Select select = new Select(roleDropdown);
        select.selectByValue("other");
        WebElement Message = webDriver.findElement(By.id("message"));
        yourname.sendKeys("Muhamad");
        Email.sendKeys("vildanmuhamed@gmail.com");
        Message.sendKeys("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.");
        WebElement sendButton = webDriver.findElement(By.xpath("//button[contains(text(),'Send Message')]"));
        sendButton.click();
    }
    @Test
    public void ItemExists() throws InterruptedException {
        WebDriverWait wait = new WebDriverWait(webDriver, Duration.ofSeconds(2));
        webDriver.get(baseUrl);
        webDriver.manage().window().maximize();
        WebElement Razer = webDriver.findElement(By.xpath("//h5[contains(text(),'Razer Kraken Kitty Headset')]"));
        Razer.click();
        WebElement productDescription = webDriver.findElement(By.cssSelector("p.text-muted"));
        String expectedText = "Customizable RGB Gaming Headset";
        Assert.assertEquals("Product description does not match!", expectedText, productDescription.getText());

    }




}
