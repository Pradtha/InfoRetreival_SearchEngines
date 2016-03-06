import java.util.Set;
import java.util.regex.Pattern;
import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.IOException;



import edu.uci.ics.crawler4j.crawler.Page;
import edu.uci.ics.crawler4j.crawler.WebCrawler;
import edu.uci.ics.crawler4j.parser.BinaryParseData;
import edu.uci.ics.crawler4j.parser.HtmlParseData;
import edu.uci.ics.crawler4j.url.WebURL;


public class MyCrawler extends WebCrawler {
	//private final static Pattern FILTERS = Pattern.compile(".*(\\.(htm|html|doc|docx|pdf))$");
	//private final static Pattern FILTERS = Pattern.compile(".*\\.(htm|html|doc|docx|pdf)$");
	//private final static Pattern FILTERS = Pattern.compile(".*\\.(doc|docx|pdf)$");
	private final static Pattern FILTERS = Pattern.compile(".*(\\.(css|js|gif|jpg|png|mp3|mp3|zip|gz|vcf))$");
	private final static String baseUrl = "http://gould.usc.edu/";
	
	/**
	* This method receives two parameters. The first parameter is the page
	* in which we have discovered this new url and the second parameter is
	* the new url. You should implement this function to specify whether
	* the given url should be crawled or not (based on your crawling logic).
	* In this example, we are instructing the crawler to ignore urls that
	* have css, js, git, ... extensions and to only accept urls that start
	* with "http://www.viterbi.usc.edu/". In this case, we didn't need the
	* referringPage parameter to make the decision.
	*/
	
	@Override
	protected void handlePageStatusCode(WebURL webUrl, int statusCode, String statusDescription) {
	    // Do nothing by default
	    // Sub-classed can override this to add their custom functionality
		String urlStr = webUrl.getURL().toLowerCase();
		if(urlStr.startsWith(baseUrl)){
	    	try{
	    		synchronized(this){
	    			BufferedWriter bw = new BufferedWriter(new FileWriter("fetch.csv",true));
					bw.append(webUrl.getURL()+","+statusCode+"\n");
					bw.close();
		    	}
			} catch(IOException ioe){
				ioe.printStackTrace();
			}
	    }
	    
	    /*try{
			BufferedWriter bw = new BufferedWriter(new FileWriter("urls.csv",true));
			bw.append(urlStr+"\n");
			bw.close();
		} catch(IOException ioe){
			ioe.printStackTrace();
		}*/
	  }
	
	protected void onPageBiggerThanMaxSize(String urlStr, long pageSize) {
	    logger.warn("Skipping a URL: {} which was bigger ( {} ) than max allowed size", urlStr, pageSize);
	  }

	
	@Override
	public boolean shouldVisit(Page referringPage, WebURL url) {
		String href = url.getURL().toLowerCase();
		//System.out.println(referringPage.getWebURL().getURL() + "  "+href);
		try{
			synchronized(this){
	    		BufferedWriter bw = new BufferedWriter(new FileWriter("urls.csv",true));
	    		bw.append(url.getURL()+","+referringPage.getStatusCode()+"\n");
	    		bw.close();
			}
		} catch(IOException ioe){
			ioe.printStackTrace();
		}
		
		if(href.startsWith(baseUrl)){
			try{
				/*BufferedWriter bw = new BufferedWriter(new FileWriter("fetch.csv",true));
				bw.append(href+","+referringPage.getStatusCode()+"\n");
				bw.close();*/
				synchronized(this){
		    		BufferedWriter bw = new BufferedWriter(new FileWriter("pagerankdata.csv",true));
		    		bw.append(referringPage.getWebURL().getURL()+","+url.getURL()+"\n");
		    		bw.close();
				}
			} catch(IOException ioe){
				ioe.printStackTrace();
			}
		}
		
		/*if(FILTERS.matcher(href).matches() && href.startsWith(baseUrl) && href.endsWith(".pdf")){
			System.out.println("sv   "+href);
		}*/
		
		//return (FILTERS.matcher(href).matches() || href.endsWith("/")) && href.startsWith(baseUrl);
		return href.startsWith(baseUrl) && !FILTERS.matcher(href).matches();
	}
	
	/**
	* This function is called when a page is fetched and ready
	* to be processed by your program.
	*/
	@Override
	public void visit(Page page) {
		String url = page.getWebURL().getURL().toLowerCase();
		//if(url.endsWith(".pdf"))// || url.endsWith(".png") || url.endsWith(".jpg"))
		//	System.out.println("vv   "+url);
		//System.out.println(page.getContentType());
		
		if (page.getParseData() instanceof HtmlParseData) {
			HtmlParseData htmlParseData = (HtmlParseData) page.getParseData();
			//String text = htmlParseData.getText();
			String html = htmlParseData.getHtml();
			Set<WebURL> links = htmlParseData.getOutgoingUrls();
			//System.out.println("Text length: " + text.length());
			//System.out.println("Html length: " + html.length());
			//System.out.println("Number of outgoing links: " + links.size());
			//System.out.println("Status Code: "+statusCode);
			//System.out.println("Content Type: " + contentType);
			
			try {
				synchronized(this){
		    		BufferedWriter bw = new BufferedWriter(new FileWriter("visit.csv",true));
		    		bw.append(page.getWebURL().getURL()+","+html.length()+","+links.size()+","+page.getContentType()+"\n");
		    		bw.close();
				}
			} catch (IOException ioe) {
				// TODO Auto-generated catch block
				ioe.printStackTrace();
			} 
		}
		
		else{
			//if(FILTERS.matcher(url).matches()){
			if(url.endsWith(".pdf") || url.endsWith(".doc") || url.endsWith(".docx")) {
				BinaryParseData binaryParseData = (BinaryParseData) page.getParseData();
				String binary = binaryParseData.getHtml();
				Set<WebURL> links = binaryParseData.getOutgoingUrls();
				try {
					BufferedWriter bw = new BufferedWriter(new FileWriter("visit.csv",true));
					bw.append(page.getWebURL().getURL()+","+binary.length()+","+links.size()+","+page.getContentType()+"\n");
					bw.close();
				} catch (IOException ioe) {
					// TODO Auto-generated catch block
					ioe.printStackTrace();
				} 
			}
		}
	}
}
