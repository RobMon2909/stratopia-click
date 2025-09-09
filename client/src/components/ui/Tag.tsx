import React from 'react';

interface TagProps {
    text: string | null;
    color: string;
    icon?: React.ReactNode; // Prop opcional para un ícono
}

const Tag: React.FC<TagProps> = ({ text, color, icon }) => {
    if (!text) {
        return <span className="text-gray-400">-</span>;
    }

    // Generamos un color de fondo más claro (pastel) a partir del color base
    const backgroundColor = color + '20'; // Añadimos 20% de opacidad en hexadecimal

    return (
        <div 
            className="flex items-center gap-x-1.5 rounded-full px-2 py-1 text-xs font-medium w-fit"
            style={{ backgroundColor }}
        >
            {icon && <span style={{ color }}>{icon}</span>}
            <span style={{ color }}>{text}</span>
        </div>
    );
};

export default Tag;