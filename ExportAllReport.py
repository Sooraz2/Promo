#!/usr/bin/python
# -*- coding: utf-8 -*-


import xlsxwriter
import sys
import pprint
import datetime
import csv
from ExcelRepo import ExportRepo
#from reportlab.lib import colors
#from reportlab.lib.pagesizes import letter, inch
#from reportlab.platypus import SimpleDocTemplate, Table, TableStyle, Paragraph, Spacer
#from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
#from reportlab.lib.units import inch
#from reportlab.lib.enums import TA_JUSTIFY
reload(sys)  # Reload does the trick!
sys.setdefaultencoding('UTF8')

argumentList = sys.argv
dateFrom = argumentList[1] if argumentList[1] != None and argumentList[1] != "None" else None
dateTo = argumentList[2] if argumentList[2] != None and argumentList[2] != "None" else None
country = argumentList[3] if argumentList[3] != None and argumentList[3] != "None" else None
operator = argumentList[4] if argumentList[4] != None and argumentList[4] != "None" else None
service = argumentList[5] if argumentList[5] != None and argumentList[5] != "None" else None
promotion = argumentList[6] if argumentList[6] != None and argumentList[6] != "None" else None
filename = argumentList[7] if argumentList[7] != None and argumentList[7] != "None" else None


#exportRepo = ExportRepo("192.168.0.145", "root", "", "unifun_promo_interface","utf8")
exportRepo = ExportRepo("localhost", "root", "jsd67FGa", "unifun_promo_interface","utf8")


details = exportRepo.findallexport(dateFrom, dateTo, country, operator, service, promotion)



Date = "Date"

DateFrom = "Date From"

DateTo = "Date To"

Country = "Country"

Operator = "Operator"

Service = "Service"

Promotion = "Promotion"

PromoText = "Promo Text"

DayOfWeek = "Day Of Week"

Views = "Views"

Activation = "Activatons"

ActivationsPercent = "Activations,%"

Comments = "Comments"

workbook = xlsxwriter.Workbook(filename)
worksheet = workbook.add_worksheet()
dateFormat = workbook.add_format({'num_format': 'yyyy-mm-dd hh:mm:ss'})
borderFormat = workbook.add_format({'border': True, 'border_color': '#000000'})
headerTitle = workbook.add_format( {'border_color': '#000000','bold': True, 'bold': 6, 'bg_color': '#F6F6F6', 'color': '#546f8e'})

worksheet.set_column('C:C', 20)
worksheet.set_column('D:D', 20)
worksheet.set_column('E:E', 20)
worksheet.set_column('F:F', 20)
worksheet.set_column('G:G', 60)
worksheet.set_column('H:H', 20)
worksheet.set_column('I:I', 20)
worksheet.set_column('J:J', 20)
worksheet.set_column('K:K', 20)
worksheet.set_column('L:L', 20)
worksheet.set_column('M:M', 20)

worksheet.write('C1', unicode("Unifun Promo Analystics", 'utf-8'))
worksheet.write('C2', unicode(DateFrom, 'utf-8'))
worksheet.write('D2', unicode(dateFrom, 'utf-8'))
worksheet.write('C3', unicode(DateTo, 'utf-8'))
worksheet.write('D3', unicode(dateTo, 'utf-8'))


x = 5
worksheet.autofilter('C5:M5')
worksheet.write('C5', unicode(Date, 'utf-8'),headerTitle)
worksheet.write('D5', unicode(Country, 'utf-8'),headerTitle)
worksheet.write('E5', unicode(Operator, 'utf-8'),headerTitle)
worksheet.write('F5', unicode(Service, 'utf-8'),headerTitle)
worksheet.write('G5', unicode(PromoText, 'utf-8'),headerTitle)
worksheet.write('H5', unicode(Promotion, 'utf-8'),headerTitle)
worksheet.write('I5', unicode(DayOfWeek, 'utf-8'),headerTitle)
worksheet.write('J5', unicode(Views, 'utf-8'),headerTitle)
worksheet.write('K5', unicode(Activation, 'utf-8'),headerTitle)
worksheet.write('L5', unicode(ActivationsPercent, 'utf-8'),headerTitle)
worksheet.write('M5', unicode(Comments, 'utf-8'),headerTitle)


if details is not False:
    for row in details:

        x += 1
        worksheet.write('C' + str(x), str(row['Date'].strftime("%Y-%m-%d")))
        worksheet.write('D' + str(x), str(row['Country']))
        worksheet.write('E' + str(x), str(row['Operator']))
        worksheet.write('F' + str(x), str(row['Service']))
        worksheet.write('G' + str(x), unicode(str(row['PromotionText']),'utf-8'))
        worksheet.write('H' + str(x), str(row['Promotion']))
        worksheet.write('I' + str(x), str(row['DayName']))
        worksheet.write('J' + str(x), str(row['Views1']))
        worksheet.write('K' + str(x), str(row['Activation1']))
        worksheet.write('L' + str(x), str(row['ActivationPercent']))
        worksheet.write('M' + str(x), str(row['comments']))

workbook.close()

print("ExportSuccess")
