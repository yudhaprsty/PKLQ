import time
from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC 
from selenium.webdriver.common.action_chains import ActionChains

user = "luthfipijo@gmail.com"
pwd = "goahead14"

driverpath="E:\\ChromeDriver"
driver = webdriver.Chrome(executable_path=r'E:/ChromeDriver/chromedriver.exe')

driver.get("http://drive.google.com")

driver.find_element_by_xpath("/html/body/section/div[2]/div/a").click()

WebDriverWait(driver, 10)

elem = wait.until(EC.element_to_be_clickable((By.ID, 'identifierId')))
elem.click()
elem.send_keys("luthfipijo@gmail.com")

driver.find_element_by_xpath("//*[@id='identifierNext']/content/span").click()
time.sleep(2)

elem = driver.find_element_by_xpath("//*[@id='password']/div[1]/div/div[1]/input")
elem.send_keys("goahead14")
time.sleep(2)

elem = driver.find_element_by_xpath("//*[@id='passwordNext']/content/span").click()
time.sleep(10)
