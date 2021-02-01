import xlrd

gramajes = [90, 115, 120, 127, 130, 140, 150, 160, 180, 195, 200, 280, 300, 320, 330, 350, 380, 400, 430, 450, 480, 530, 550, 600]
numero_uniones = {"(u)": 1, "(2u)": 2, "(3u)": 3}

def getNum(string_num, nums):
    for x in range(len(nums)):
        gramaje = str(nums[x])
        if gramaje in string_num:
            return gramaje
    return string_num

def rollosTurno(privote, sheet, num_maquina):
    i = 14
    acc = 0
    suma_produccion = 0
    fecha = "'" + sheet.cell_value(9, 4) + "'"
    print()
    while ("º" not in sheet.cell_value(i, privote)):
        id_rollo = str(sheet.cell_value(i, privote+2))
        if(id_rollo == ""):
            i += 1
        else:
            almacen = "2"
            uniones  = 0
            if "inv" in id_rollo:
                almacen = "3"
                id_rollo = id_rollo.replace("inv", '')
            for key in numero_uniones:
                if key in id_rollo:
                    uniones = numero_uniones[key]
                    id_rollo = id_rollo.replace(key, '')
            print(int(float(id_rollo)), end=', ') #ID rollo
            print(str(num_maquina), end=", ") #num_maquina
            print("'" + getNum(sheet.cell_value(i, privote), gramajes) + "'", end=', ') #Gramaje
            print("'" + str(sheet.cell_value(i, privote+4)) + "'", end=', ') #Ancho
            print(int(float(sheet.cell_value(i, privote+3))), end=', ') #Peso
            suma_produccion += int(sheet.cell_value(i, privote+3))
            print(almacen + ", " + str(uniones), end=', ') #Turno y uniones
            print(fecha, end=', ')  #Fecha
            print("'" + str(int(sheet.cell_value(i, privote+1))) + "'", end=', ') #Num_Cliente
            print("'Almacen', '4', '1', '"+str(sheet.cell_value(i, privote+5))+"')")
            acc += 1
            i += 1
    # print("Rollos del turno son -->", acc, " [", f"{suma_produccion:,}", "] kg")
    return suma_produccion




def readExcel(path, num_maquina,days):
    xlsx_path   = (path)
    wb          = xlrd.open_workbook(xlsx_path)
    total       = 0     #total sum of production in the machine
    
    for x in range(days):
        sheet = wb.sheet_by_index(x)    #Open the day sheet
        total += rollosTurno(1, sheet, num_maquina)   #Turno 1
        total += rollosTurno(8, sheet, num_maquina)   #Turno 2
        total += rollosTurno(15, sheet,num_maquina)   #Turno 3
        # print()
    print("Produccion total = [" +  f"{total:,}" + "]kg")
