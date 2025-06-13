export default function Card({ header, footer, children }) {
    return (
        <div className="divide-y divide-gray-200 overflow-hidden rounded-lg bg-white shadow-sm my-2">
            {header && <div className="px-4 py-5 sm:px-6">{header}</div>}
            <div className="px-4 py-5 sm:p-6">{children}</div>
            {footer && <div className="px-4 py-4 sm:px-6">{footer}</div>}
        </div>
    );
}
