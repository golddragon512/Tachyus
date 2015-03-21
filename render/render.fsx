#r "System.Xml"
#r "System.Xml.Linq.dll"

open System
open System.IO
open System.Xml

// README: run this file from the root directory to rerender the site from templates

// traverses directory tree
let rec getAllFiles dir pattern =
    seq { yield! Directory.EnumerateFiles(dir, pattern)
          for d in Directory.EnumerateDirectories(dir) do
            yield! getAllFiles d pattern }

// loads replacement xml from template file
let getReplacementNode (fileName:string) =
    printfn "loading template %s" fileName
    let xmlDoc = new XmlDocument()
    xmlDoc.Load(fileName)
    xmlDoc.FirstChild

// replaces first occurence of tag in xml file with speficied template file content
let replaceFirst (xmlDoc:XmlDocument) tagName fileName =
    let matchNodes = xmlDoc.GetElementsByTagName(tagName)
    if matchNodes.Count > 0 then
        let oldNode = matchNodes.[0]
        let parent = oldNode.ParentNode
        let importNode = xmlDoc.ImportNode(getReplacementNode fileName, true)
        parent.ReplaceChild(importNode, oldNode) |> ignore   

// executes the defined set of rendering steps we want
let reRender (fileName:string) = 
    let xmlDoc = new XmlDocument()
    printfn "loading %s" fileName
    xmlDoc.Load(fileName)
    printfn "rendering %s" fileName
    replaceFirst xmlDoc "nav" "render/nav.xml"
    replaceFirst xmlDoc "footer" "render/footer.xml"

    xmlDoc.Save(fileName)
    printfn "saved %s!" fileName

getAllFiles Environment.CurrentDirectory "*.html"
|> Seq.iter reRender